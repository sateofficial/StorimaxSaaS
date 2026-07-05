<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::with(['project', 'tags'])
            ->where('is_public', true)
            ->latest()
            ->get();

        return view('client.portfolios.index', compact('portfolios'));
    }

    public function show(Portfolio $portfolio)
    {
        if (!$portfolio->is_public) {
            abort(404);
        }

        $portfolio->load(['project', 'creator', 'tags']);

        return view('client.portfolios.show', compact('portfolio'));
    }
}
