<?php

use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Load semua route file
require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/crew.php';
require __DIR__ . '/client.php';