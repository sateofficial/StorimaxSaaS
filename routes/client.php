<?php

use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\InvoiceController;
use App\Http\Controllers\Client\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Invoice (view only)
        Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');

        // Portfolio (view only, publik saja)
        Route::get('portfolios', [PortfolioController::class, 'index'])->name('portfolios.index');
        Route::get('portfolios/{portfolio}', [PortfolioController::class, 'show'])->name('portfolios.show');
    });