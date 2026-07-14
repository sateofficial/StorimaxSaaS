<?php

namespace App\Http\Controllers\Crew;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function updateStatus(Request $request, Job $job)
    {
        // Pastikan hanya job miliknya sendiri
        if ($job->assigned_to !== auth()->id()) {
            abort(403, 'Kamu tidak memiliki akses ke job ini.');
        }

        $request->validate([
            'status' => 'required|in:inprogress,review,done',
            'note'   => 'nullable|string|max:500',
        ]);

        $oldStatus = $job->status->value;
        $newStatus = $request->status;

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

        // ── Notifikasi ke Admin & Atasan ──
        // Kirim untuk SEMUA perubahan status agar progress terpantau
        $statusLabel = match($newStatus) {
            'inprogress' => 'In Progress',
            'review'     => 'Review',
            'done'       => 'Done',
            'todo'       => 'To Do',
            default      => ucfirst($newStatus),
        };

        $crewName = auth()->user()->name;

        NotificationHelper::notifyAdmins(
            type: 'job_' . $newStatus,
            title: "Job {$statusLabel}: {$job->title}",
            message: "{$crewName} mengupdate job \"{$job->title}\" menjadi {$statusLabel}.",
            data: ['job_id' => $job->id, 'project_id' => $job->project_id],
            actionUrl: route('admin.jobs.show', $job),
        );

        // ── Notifikasi ke Client ──
        $client = optional($job->project)->client;
        if ($client && $client->user_id) {
            NotificationHelper::notify(
                userId: $client->user_id,
                type: 'job_' . $newStatus,
                title: "Update Progress: {$job->title}",
                message: "Progress job \"{$job->title}\" di project \"" . ($job->project?->name ?? '—') . "\" berubah menjadi {$statusLabel}.",
                data: ['job_id' => $job->id, 'project_id' => $job->project_id],
                actionUrl: route('client.dashboard'),
            );
        }

        return back()->with('success', 'Status job berhasil diupdate.');
    }
}
