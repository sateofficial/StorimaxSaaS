<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin,atasan', 'log.activity'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Users
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])
            ->name('users.toggle-active');

        // Departments
        Route::resource('departments', DepartmentController::class);

        // Clients
        Route::resource('clients', ClientController::class);

        // Projects
        Route::resource('projects', ProjectController::class);
        Route::patch('projects/{project}/status', [ProjectController::class, 'updateStatus'])
            ->name('projects.update-status');

        // Project Teams
        Route::post('projects/{project}/teams', [\App\Http\Controllers\Admin\ProjectTeamController::class, 'store'])
           ->name('projects.teams.store');
        Route::delete('projects/{project}/teams/{team}', [\App\Http\Controllers\Admin\ProjectTeamController::class, 'destroy'])
            ->name('projects.teams.destroy');

        // Project Team Members
        Route::post('projects/{project}/teams/{team}/members', [\App\Http\Controllers\Admin\ProjectTeamController::class, 'addMember'])
          ->name('projects.teams.members.store');
        Route::delete('projects/{project}/teams/{team}/members/{member}', [\App\Http\Controllers\Admin\ProjectTeamController::class, 'removeMember'])
         ->name('projects.teams.members.destroy');

        // Jobs
        Route::resource('projects.jobs', JobController::class)->shallow();
        Route::patch('jobs/{job}/status', [JobController::class, 'updateStatus'])
            ->name('jobs.update-status');

        // Invoices
        Route::resource('invoices', InvoiceController::class);
        Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])
            ->name('invoices.update-status');
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])
            ->name('invoices.pdf');

        // Portfolios
        Route::resource('portfolios', PortfolioController::class);
        Route::patch('portfolios/{portfolio}/toggle-public', [PortfolioController::class, 'togglePublic'])
            ->name('portfolios.toggle-public');

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/crew/{user}', [ReportController::class, 'crew'])->name('reports.crew');
        Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    });