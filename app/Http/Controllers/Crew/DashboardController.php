<?php

namespace App\Http\Controllers\Crew;

use App\Enums\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\Job;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalJobs = Job::where('assigned_to', $userId)->count();

        $activeJobs = Job::where('assigned_to', $userId)
            ->whereIn('status', [JobStatus::TODO, JobStatus::INPROGRESS, JobStatus::REVIEW])
            ->count();

        $inProgress = Job::where('assigned_to', $userId)
            ->where('status', JobStatus::INPROGRESS)
            ->count();

        $doneJobs = Job::where('assigned_to', $userId)
            ->where('status', JobStatus::DONE)
            ->count();

        $recentJobs = Job::with('project')
            ->where('assigned_to', $userId)
            ->whereIn('status', [JobStatus::TODO, JobStatus::INPROGRESS, JobStatus::REVIEW])
            ->latest()
            ->take(5)
            ->get();

        return view('crew.dashboard.index', compact(
            'totalJobs', 'activeJobs', 'inProgress', 'doneJobs', 'recentJobs'
        ));
    }
}
