<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\InvoiceTemplateController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

// ── Shared read-only routes (Admin & Atasan) ─────────────
Route::middleware(['auth', 'role:admin,atasan', 'log.activity'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Static GET routes BEFORE parameterized routes to prevent conflicts
        Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
        Route::get('invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
        Route::get('invoices/templates', [InvoiceTemplateController::class, 'index'])->name('invoices.templates.index');
        Route::get('invoices/templates/create', [InvoiceTemplateController::class, 'create'])->name('invoices.templates.create');
        Route::get('invoices/templates/{template}/edit', [InvoiceTemplateController::class, 'edit'])->name('invoices.templates.edit');
        Route::get('portfolios/create', [PortfolioController::class, 'create'])->name('portfolios.create');
        Route::get('jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit');
        Route::get('projects/{project}/jobs/create', [JobController::class, 'create'])->name('projects.jobs.create');
        Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::get('portfolios/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('portfolios.edit');

        // Parameterized GET routes
        Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('jobs', [JobController::class, 'index'])->name('jobs.index');
        Route::get('jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
        Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
        Route::get('portfolios', [PortfolioController::class, 'index'])->name('portfolios.index');
        Route::get('portfolios/{portfolio}', [PortfolioController::class, 'show'])->name('portfolios.show');

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/crew/{user}', [ReportController::class, 'crew'])->name('reports.crew');
        Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    });

// ── Admin-only mutation routes ───────────────────────────
Route::middleware(['auth', 'role:admin', 'log.activity'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Users
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

        // Clients
        Route::resource('clients', ClientController::class);

        // Projects (mutations)
        Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
        Route::patch('projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.update-status');

        // Jobs (mutations)
        Route::post('projects/{project}/jobs', [JobController::class, 'store'])->name('projects.jobs.store');
        Route::put('jobs/{job}', [JobController::class, 'update'])->name('jobs.update');
        Route::delete('jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');
        Route::patch('jobs/{job}/status', [JobController::class, 'updateStatus'])->name('jobs.update-status');

        // Invoices (mutations)
        Route::post('invoices', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::put('invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
        Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
        Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.update-status');

        // Invoice Templates (mutations)
        Route::post('invoices/templates', [InvoiceTemplateController::class, 'store'])->name('invoices.templates.store');
        Route::put('invoices/templates/{template}', [InvoiceTemplateController::class, 'update'])->name('invoices.templates.update');
        Route::patch('invoices/templates/{template}/activate', [InvoiceTemplateController::class, 'activate'])->name('invoices.templates.activate');
        Route::delete('invoices/templates/{template}', [InvoiceTemplateController::class, 'destroy'])->name('invoices.templates.destroy');

        // Portfolios (mutations)
        Route::post('portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');
        Route::put('portfolios/{portfolio}', [PortfolioController::class, 'update'])->name('portfolios.update');
        Route::delete('portfolios/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolios.destroy');
        Route::patch('portfolios/{portfolio}/toggle-public', [PortfolioController::class, 'togglePublic'])->name('portfolios.toggle-public');
    });
