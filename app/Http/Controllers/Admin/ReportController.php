<?php

namespace App\Http\Controllers\Admin;

use App\Enums\JobStatus;
use App\Enums\InvoiceStatus;
use App\Enums\ProjectStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Project;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // ── Ringkasan ────────────────────────────────────
        $totalCrew = User::where('role', UserRole::CREW)
            ->where('is_active', true)
            ->count();

        $activeProjects = Project::whereIn('status', [
            ProjectStatus::ACTIVE, ProjectStatus::REVIEW,
        ])->count();

        $totalJobs = Job::count();

        $doneJobs = Job::where('status', JobStatus::DONE)->count();
        $completionRate = $totalJobs > 0 ? round(($doneJobs / $totalJobs) * 100) : 0;

        $pendingInvoices = Invoice::whereIn('status', [
            InvoiceStatus::DRAFT, InvoiceStatus::SENT, InvoiceStatus::DP_PAID,
        ])->count();

        // ── Performa per Crew ────────────────────────────
        $crewMembers = User::with('department')
            ->where('role', UserRole::CREW)
            ->where('is_active', true)
            ->withCount([
                'jobs',
                'jobs as todo_count' => fn($q) => $q->where('status', JobStatus::TODO),
                'jobs as inprogress_count' => fn($q) => $q->where('status', JobStatus::INPROGRESS),
                'jobs as review_count' => fn($q) => $q->where('status', JobStatus::REVIEW),
                'jobs as done_count' => fn($q) => $q->where('status', JobStatus::DONE),
            ])
            ->get()
            ->map(function ($crew) {
                $crew->completion_rate = $crew->jobs_count > 0
                    ? round(($crew->done_count / $crew->jobs_count) * 100)
                    : 0;
                return $crew;
            });

        // ── Departemen ───────────────────────────────────
        $departments = Department::withCount(['users' => fn($q) => $q->where('role', UserRole::CREW)])
            ->get();

        return view('admin.reports.index', compact(
            'totalCrew', 'activeProjects', 'totalJobs', 'completionRate',
            'pendingInvoices', 'crewMembers', 'departments'
        ));
    }

    public function crew(User $user)
    {
        if (!$user->isCrew()) {
            abort(404);
        }

        $jobs = Job::with(['project', 'team', 'assignee'])
            ->where('assigned_to', $user->id)
            ->latest()
            ->get();

        $stats = [
            'total'     => $jobs->count(),
            'todo'      => $jobs->where('status', JobStatus::TODO)->count(),
            'inprogress'=> $jobs->where('status', JobStatus::INPROGRESS)->count(),
            'review'    => $jobs->where('status', JobStatus::REVIEW)->count(),
            'done'      => $jobs->where('status', JobStatus::DONE)->count(),
            'overdue'   => $jobs->filter(fn($j) => $j->isOverdue())->count(),
            'rate'      => $jobs->count() > 0
                ? round(($jobs->where('status', JobStatus::DONE)->count() / $jobs->count()) * 100)
                : 0,
        ];

        return view('admin.reports.crew', compact('user', 'jobs', 'stats'));
    }

    public function exportPdf()
    {
        $crewMembers = User::with('department')
            ->where('role', UserRole::CREW)
            ->where('is_active', true)
            ->withCount([
                'jobs',
                'jobs as todo_count' => fn($q) => $q->where('status', JobStatus::TODO),
                'jobs as inprogress_count' => fn($q) => $q->where('status', JobStatus::INPROGRESS),
                'jobs as review_count' => fn($q) => $q->where('status', JobStatus::REVIEW),
                'jobs as done_count' => fn($q) => $q->where('status', JobStatus::DONE),
            ])
            ->get()
            ->map(function ($crew) {
                $crew->completion_rate = $crew->jobs_count > 0
                    ? round(($crew->done_count / $crew->jobs_count) * 100)
                    : 0;
                return $crew;
            });

        $totalJobs = Job::count();
        $doneJobs = Job::where('status', JobStatus::DONE)->count();
        $completionRate = $totalJobs > 0 ? round(($doneJobs / $totalJobs) * 100) : 0;

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'crewMembers', 'totalJobs', 'doneJobs', 'completionRate'
        ));

        return $pdf->download('laporan-kinerja-crew-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        $crewMembers = User::with('department')
            ->where('role', UserRole::CREW)
            ->where('is_active', true)
            ->withCount([
                'jobs',
                'jobs as todo_count' => fn($q) => $q->where('status', JobStatus::TODO),
                'jobs as inprogress_count' => fn($q) => $q->where('status', JobStatus::INPROGRESS),
                'jobs as review_count' => fn($q) => $q->where('status', JobStatus::REVIEW),
                'jobs as done_count' => fn($q) => $q->where('status', JobStatus::DONE),
            ])
            ->get()
            ->map(function ($crew) {
                $crew->completion_rate = $crew->jobs_count > 0
                    ? round(($crew->done_count / $crew->jobs_count) * 100)
                    : 0;
                return $crew;
            });

        $filename = 'laporan-kinerja-crew-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($crewMembers) {
            $handle = fopen('php://output', 'w');

            // BOM untuk UTF-8 (Excel compatibility)
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($handle, [
                'Nama', 'Departemen', 'Total Job', 'To Do',
                'In Progress', 'Review', 'Done', 'Completion Rate',
            ]);

            foreach ($crewMembers as $crew) {
                fputcsv($handle, [
                    $crew->name,
                    $crew->department?->name ?? '-',
                    $crew->jobs_count,
                    $crew->todo_count,
                    $crew->inprogress_count,
                    $crew->review_count,
                    $crew->done_count,
                    $crew->completion_rate . '%',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
