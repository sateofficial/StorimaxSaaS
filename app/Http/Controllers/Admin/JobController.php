<?php

namespace App\Http\Controllers\Admin;

use App\Enums\JobStatus;
use App\Enums\JobPriority;
use App\Enums\UserRole;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with(['project.client', 'assignee'])
            ->latest()
            ->get();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create(Project $project)
    {
        $crews = User::where('role', UserRole::CREW)
                     ->where('is_active', true)
                     ->orderBy('name')
                     ->get();

        return view('admin.jobs.create', compact('project', 'crews'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority'    => 'required|in:low,medium,high,urgent',
            'deadline'    => 'nullable|date',
            'notes'       => 'nullable|string',
            'gdrive_link' => 'nullable|string|max:500',
        ]);

        $job = Job::create([
            'project_id'  => $project->id,
            'assigned_to' => $request->assigned_to ?: null,
            'created_by'  => auth()->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => JobStatus::TODO,
            'priority'    => $request->priority,
            'deadline'    => $request->deadline,
            'notes'       => $request->notes,
            'gdrive_link' => $request->gdrive_link,
        ]);

        // Notifikasi ke crew yang diassign
        if ($request->assigned_to) {
            NotificationHelper::notify(
                userId: $request->assigned_to,
                type: 'job_assigned',
                title: 'Job Baru: ' . $job->title,
                message: "Kamu mendapat job baru di project {$project->name}.",
                data: ['job_id' => $job->id, 'project_id' => $project->id],
                actionUrl: route('crew.jobs.show', $job),
            );
        }

        return redirect()->route('admin.projects.show', $project)
            ->with('success', 'Job berhasil ditambahkan.');
    }

    public function show(Job $job)
    {
        $job->load([
            'project.client',
            'assignee',
            'logs.user',
            'creator',
        ]);

        return view('admin.jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $crews = User::where('role', UserRole::CREW)
                     ->where('is_active', true)
                     ->orderBy('name')
                     ->get();

        return view('admin.jobs.edit', compact('job', 'crews'));
    }

    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority'    => 'required|in:low,medium,high,urgent',
            'deadline'    => 'nullable|date',
            'notes'       => 'nullable|string',
            'gdrive_link' => 'nullable|string|max:500',
        ]);

        $job->update($request->only([
            'title', 'description', 'assigned_to',
            'priority', 'deadline', 'notes', 'gdrive_link',
        ]));

        return redirect()->route('admin.jobs.show', $job)
            ->with('success', 'Job berhasil diupdate.');
    }

    public function destroy(Job $job)
    {
        $project = $job->project;
        $job->delete();

        if ($project) {
            return redirect()->route('admin.projects.show', $project)
                ->with('success', 'Job berhasil dihapus.');
        }

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job berhasil dihapus.');
    }

    public function updateStatus(Request $request, Job $job)
    {
        $request->validate([
            'status' => 'required|in:todo,inprogress,review,done',
        ]);

        $oldStatus = $job->status->value;
        $newStatus = $request->status;

        // Auto set timestamps
        $data = ['status' => $newStatus];
        if ($newStatus === 'inprogress' && !$job->started_at) {
            $data['started_at'] = now();
        }
        if ($newStatus === 'done' && !$job->completed_at) {
            $data['completed_at'] = now();
        }

        $job->update($data);

        // Log perubahan status
        $job->logs()->create([
            'user_id'    => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'note'       => $request->note,
        ]);

        // Notifikasi ke assignee jika status berubah
        if ($job->assigned_to && $job->assigned_to !== auth()->id()) {
            $statusLabel = match($newStatus) {
                'inprogress' => 'In Progress',
                'review'     => 'Review',
                'done'       => 'Done',
                default      => ucfirst($newStatus),
            };
            NotificationHelper::notify(
                userId: $job->assigned_to,
                type: 'job_status_' . $newStatus,
                title: 'Job diupdate ke ' . $statusLabel,
                message: 'Status job "' . $job->title . '" diubah menjadi ' . $statusLabel . '.',
                data: ['job_id' => $job->id, 'project_id' => $job->project_id],
                actionUrl: route('crew.jobs.show', $job),
            );
        }

        return back()->with('success', 'Status job berhasil diupdate.');
    }
}