<?php

use App\Http\Controllers\Crew\DashboardController;
use App\Http\Controllers\Crew\JobController;
use App\Http\Controllers\Crew\ProgressController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:crew', 'log.activity'])
    ->prefix('crew')
    ->name('crew.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Jobs (lihat job sendiri saja)
        Route::get('jobs', [JobController::class, 'index'])->name('jobs.index');
        Route::get('jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

        // Progress update
        Route::patch('jobs/{job}/status', [ProgressController::class, 'updateStatus'])
            ->name('jobs.update-status');
        Route::post('jobs/{job}/attachments', [ProgressController::class, 'uploadAttachment'])
            ->name('jobs.attachments.upload');
        Route::delete('jobs/{job}/attachments/{attachment}', [ProgressController::class, 'deleteAttachment'])
            ->name('jobs.attachments.delete');
    });