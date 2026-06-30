<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatus;
use App\Enums\JobStatus;
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

        return view('admin.dashboard.index', compact(
            'totalProjects', 'activeJobs', 'pendingInvoices', 'totalCrew'
        ));
    }
}