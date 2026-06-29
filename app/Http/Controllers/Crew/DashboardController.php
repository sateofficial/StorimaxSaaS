<?php

namespace App\Http\Controllers\Crew;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('crew.dashboard.index');
    }
}