<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['client', 'project'])->latest()->get();
        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $projects = Project::with('client')->get();
        return view('admin.invoices.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id'    => 'required|exists:projects,id',
            'invoice_date'  => 'required|date',
            'session_date'  => 'nullable|date',
            'due_date'      => 'nullable|date',
            'pph_rate'      => 'required|numeric|min:0|max:100',
            'dp_amount'     => 'nullable|numeric|min:0',
            'bank_name'     => 'nullable|string|max:100',
            'bank_account'  => 'nullable|string|max:50',
            'bank_holder'   => 'nullable|string|max:150',
            'payment_notes' => 'nullable|string',
            'items'                  => 'required|array|min:1',
            'items.*.service_name'   => 'required|string|max:200',
            'items.*.description'    => 'nullable|string',
            'items.*.price'          => 'required|numeric|min:0',
            'items.*.disc_percent'   => 'nullable|numeric|min:0|max:100',
        ]);

        $project = Project::findOrFail($request->project_id);

        // Generate invoice number: INV/STX/2026/001
        $year  = date('Y');
        $count = Invoice::whereYear('created_at', $year)->count() + 1;
        $invoiceNumber = 'INV/STX/' . $year . '/' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Hitung subtotal dari semua items
        $subtotal = 0;
        $itemsData = [];
        foreach ($request->items as $item) {
            $price       = (float) $item['price'];
            $discPercent = (float) ($item['disc_percent'] ?? 0);
            $discAmount  = $price * $discPercent / 100;
            $total       = $price - $discAmount;
            $subtotal   += $total;

            $itemsData[] = [
                'service_name' => $item['service_name'],
                'description'  => $item['description'] ?? null,
                'price'        => $price,
                'disc_percent' => $discPercent,
                'disc_amount'  => $discAmount,
                'total'        => $total,
            ];
        }

        $pphRate   = (float) $request->pph_rate;
        $pphAmount = $subtotal * $pphRate / 100;
        $total     = $subtotal - $pphAmount;
        $dpAmount  = (float) ($request->dp_amount ?? 0);
        $remaining = $total - $dpAmount;

        $invoice = Invoice::create([
            'project_id'     => $project->id,
            'client_id'      => $project->client_id,
            'created_by'     => auth()->id(),
            'invoice_number' => $invoiceNumber,
            'invoice_date'   => $request->invoice_date,
            'session_date'   => $request->session_date,
            'due_date'       => $request->due_date,
            'subtotal'       => $subtotal,
            'pph_rate'       => $pphRate,
            'pph_amount'     => $pphAmount,
            'total'          => $total,
            'dp_amount'      => $dpAmount,
            'dp_paid'        => 0,
            'remaining'      => $remaining,
            'status'         => InvoiceStatus::DRAFT,
            'bank_name'      => $request->bank_name ?? 'BCA',
            'bank_account'   => $request->bank_account ?? '0191040839',
            'bank_holder'    => $request->bank_holder ?? 'PT JALUR TENGAH KREASINDO',
            'payment_notes'  => $request->payment_notes
                ?? 'Mohon lakukan pembayaran maksimal 1x24 jam setelah invoice diterima. Terima kasih telah mempercayakan momen berharga Anda bersama Storimax.',
        ]);

        foreach ($itemsData as $index => $item) {
            $invoice->items()->create(array_merge($item, ['sort_order' => $index]));
        }

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice berhasil dibuat.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'items', 'creator']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:draft,sent,dp_paid,paid,overdue',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'sent' && !$invoice->sent_at) {
            $data['sent_at'] = now();
        }
        if ($request->status === 'dp_paid' && !$invoice->dp_paid_at) {
            $data['dp_paid_at'] = now();
            $data['dp_paid']    = $invoice->dp_amount;
        }
        if ($request->status === 'paid' && !$invoice->paid_at) {
            $data['paid_at'] = now();
            $data['dp_paid'] = $invoice->total;
        }

        $invoice->update($data);

        return back()->with('success', 'Status invoice berhasil diupdate.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'items']);
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));
        return $pdf->download($invoice->invoice_number . '.pdf');
    }
}