<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $client = auth()->user()->client;

        if (!$client) {
            return view('client.invoices.index', ['invoices' => collect()]);
        }

        $invoices = Invoice::with(['project', 'items'])
            ->where('client_id', $client->id)
            ->latest()
            ->get();

        return view('client.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $client = auth()->user()->client;

        if (!$client || $invoice->client_id !== $client->id) {
            abort(403, 'Kamu tidak memiliki akses ke invoice ini.');
        }

        $invoice->load(['project', 'items']);

        return view('client.invoices.show', compact('invoice'));
    }
}
