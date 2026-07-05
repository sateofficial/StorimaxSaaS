<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Portfolio;

class DashboardController extends Controller
{
    public function index()
    {
        $client = auth()->user()->client;

        $totalInvoices = 0;
        $totalPaid = 0;
        $totalProjects = 0;
        $totalPortfolios = 0;

        if ($client) {
            $totalInvoices = Invoice::where('client_id', $client->id)->count();
            $totalPaid = Invoice::where('client_id', $client->id)
                ->where('status', 'paid')
                ->sum('total');
            $totalProjects = $client->projects()->count();
            $totalPortfolios = Portfolio::whereHas('project', function ($q) use ($client) {
                $q->where('client_id', $client->id);
            })->where('is_public', true)->count();

            $recentInvoices = Invoice::with('project')
                ->where('client_id', $client->id)
                ->latest()
                ->take(5)
                ->get();
        } else {
            $recentInvoices = collect();
        }

        return view('client.dashboard.index', compact(
            'totalInvoices', 'totalPaid', 'totalProjects',
            'totalPortfolios', 'recentInvoices'
        ));
    }
}
