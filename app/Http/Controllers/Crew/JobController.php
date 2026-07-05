<?php

namespace App\Http\Controllers\Crew;

use App\Http\Controllers\Controller;
use App\Models\Job;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with(['project.client', 'team'])
            ->where('assigned_to', auth()->id())
            ->latest()
            ->get();

        return view('crew.jobs.index', compact('jobs'));
    }

    public function show(Job $job)
    {
        // Pastikan crew hanya bisa lihat job miliknya sendiri
        if ($job->assigned_to !== auth()->id()) {
            abort(403, 'Kamu tidak memiliki akses ke job ini.');
        }

        $job->load([
            'project.client',
            'team',
            'logs.user',
            'attachments.uploader',
        ]);

        return view('crew.jobs.show', compact('job'));
    }
}
