<?php

namespace App\Http\Controllers\Crew;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgressController extends Controller
{
    public function updateStatus(Request $request, Job $job)
    {
        // Pastikan hanya job miliknya sendiri
        if ($job->assigned_to !== auth()->id()) {
            abort(403, 'Kamu tidak memiliki akses ke job ini.');
        }

        $request->validate([
            'status' => 'required|in:todo,inprogress,review,done',
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

        // Notifikasi ke admin saat crew update ke review/done
        if (in_array($newStatus, ['review', 'done'])) {
            $statusLabel = match($newStatus) {
                'review' => 'Review',
                'done'   => 'Done',
                default  => ucfirst($newStatus),
            };
            NotificationHelper::notifyAdmins(
                type: 'job_' . $newStatus,
                title: "Job {$statusLabel}: {$job->title}",
                message: auth()->user()->name . " mengupdate job \"{$job->title}\" menjadi {$statusLabel}.",
                data: ['job_id' => $job->id, 'project_id' => $job->project_id],
                actionUrl: route('admin.jobs.show', $job),
            );
        }

        return back()->with('success', 'Status job berhasil diupdate.');
    }

    public function uploadAttachment(Request $request, Job $job)
    {
        if ($job->assigned_to !== auth()->id()) {
            abort(403, 'Kamu tidak memiliki akses ke job ini.');
        }

        $request->validate([
            'file'        => 'required|file|max:10240', // max 10MB
            'category'    => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');
        $path = $file->store('job-attachments', 'public');

        $job->attachments()->create([
            'uploaded_by' => auth()->id(),
            'file_name'   => $file->getClientOriginalName(),
            'file_path'   => $path,
            'file_type'   => $file->getMimeType(),
            'file_size'   => $file->getSize(),
            'category'    => $request->category,
            'description' => $request->description,
        ]);

        return back()->with('success', 'File berhasil diupload.');
    }

    public function deleteAttachment(Job $job, JobAttachment $attachment)
    {
        if ($job->assigned_to !== auth()->id()) {
            abort(403, 'Kamu tidak memiliki akses ke job ini.');
        }

        if ($attachment->job_id !== $job->id) {
            abort(404);
        }

        // Hapus file dari storage
        Storage::disk('public')->delete($attachment->file_path);

        $attachment->delete();

        return back()->with('success', 'File berhasil dihapus.');
    }
}
