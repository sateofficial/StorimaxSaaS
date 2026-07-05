<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatus;
use App\Enums\JobStatus;
use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Project;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProjects = Project::count();

        $activeJobs = Job::whereIn('status', [JobStatus::TODO, JobStatus::INPROGRESS, JobStatus::REVIEW])
            ->count();

        $pendingInvoices = Invoice::whereIn('status', [InvoiceStatus::DRAFT, InvoiceStatus::SENT, InvoiceStatus::DP_PAID])
            ->count();

        $totalCrew = User::where('role', UserRole::CREW)
            ->where('is_active', true)
            ->count();

        // Sort preference
        $sort = request('sort', 'progress_desc');

        // Projects with job progress
        $projects = Project::with('client')
            ->withCount([
                'jobs',
                'jobs as done_jobs_count' => function ($q) {
                    $q->where('status', JobStatus::DONE);
                },
            ])
            ->whereIn('status', [ProjectStatus::ACTIVE, ProjectStatus::REVIEW, ProjectStatus::DONE])
            ->latest('updated_at')
            ->take(10)
            ->get()
            ->map(function ($project) {
                $project->progress = $project->jobs_count > 0
                    ? round(($project->done_jobs_count / $project->jobs_count) * 100)
                    : 0;
                return $project;
            });

        // Apply sorting
        $projects = match ($sort) {
            'progress_asc'  => $projects->sortBy('progress'),
            'progress_desc' => $projects->sortByDesc('progress'),
            default         => $projects->sortByDesc('progress'),
        };

        return view('admin.dashboard.index', compact(
            'totalProjects', 'activeJobs', 'pendingInvoices', 'totalCrew', 'projects', 'sort'
        ));
    }
}