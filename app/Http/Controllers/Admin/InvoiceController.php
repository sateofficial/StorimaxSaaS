<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatus;
use App\Helpers\MarkdownHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceTemplate;
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

        $year   = date('Y');
        $prefix = 'INV/STX/' . $year . '/';

        // Cari nomor urut TERTINGGI yang sudah ada (abaikan soft-delete & gap)
        $maxNumber = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(invoice_number, -3) AS UNSIGNED) DESC')
            ->value('invoice_number');

        $number       = $maxNumber ? ((int) substr($maxNumber, -3)) + 1 : 1;
        $invoiceNumber = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);

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

    public function edit(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'items', 'creator']);
        $projects = Project::with('client')->get();
        return view('admin.invoices.edit', compact('invoice', 'projects'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_date'  => 'required|date',
            'session_date'  => 'nullable|date',
            'due_date'      => 'nullable|date',
            'pph_rate'      => 'required|numeric|min:0|max:100',
            'dp_amount'     => 'nullable|numeric|min:0',
            'bank_name'     => 'nullable|string|max:100',
            'bank_account'  => 'nullable|string|max:50',
            'bank_holder'   => 'nullable|string|max:150',
            'payment_notes' => 'nullable|string',
        ]);

        // Recalculate financials
        $subtotal = $invoice->items->sum('total');
        $pphRate   = (float) $request->pph_rate;
        $pphAmount = $subtotal * $pphRate / 100;
        $total     = $subtotal - $pphAmount;
        $dpAmount  = (float) ($request->dp_amount ?? 0);
        $remaining = $total - $dpAmount;

        $invoice->update([
            'invoice_date'  => $request->invoice_date,
            'session_date'  => $request->session_date,
            'due_date'      => $request->due_date,
            'pph_rate'      => $pphRate,
            'pph_amount'    => $pphAmount,
            'total'         => $total,
            'dp_amount'     => $dpAmount,
            'remaining'     => $remaining,
            'bank_name'     => $request->bank_name ?? 'BCA',
            'bank_account'  => $request->bank_account ?? '0191040839',
            'bank_holder'   => $request->bank_holder ?? 'PT JALUR TENGAH KREASINDO',
            'payment_notes' => $request->payment_notes,
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice berhasil diperbarui.');
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

        // Notifikasi ke client saat invoice dikirim
        if ($request->status === 'sent' && $invoice->client && $invoice->client->user_id) {
            NotificationHelper::notify(
                userId: $invoice->client->user_id,
                type: 'invoice_sent',
                title: 'Invoice Baru: ' . $invoice->invoice_number,
                message: "Invoice baru untuk project " . ($invoice->project?->name ?? '—') . " telah diterbitkan.",
                data: ['invoice_id' => $invoice->id, 'project_id' => $invoice->project_id],
                actionUrl: route('client.invoices.show', $invoice),
            );
        }

        if (in_array($request->status, ['dp_paid', 'paid'])) {
            $statusLabel = $request->status === 'dp_paid' ? 'DP dibayar' : 'Lunas';
            NotificationHelper::notifyAdmins(
                type: 'invoice_' . $request->status,
                title: "Invoice {$statusLabel}: {$invoice->invoice_number}",
                message: "Invoice {$invoice->invoice_number} telah ditandai {$statusLabel}.",
                data: ['invoice_id' => $invoice->id, 'project_id' => $invoice->project_id],
                actionUrl: route('admin.invoices.show', $invoice),
            );
        }

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

        // Baca template dari DB (aktif), fallback ke file
        $template = InvoiceTemplate::getActive();
        $markdown = $template->content ?? '';
        if (empty(trim($markdown))) {
            $templatePath = resource_path('views/admin/invoices/template.md');
            $markdown = file_exists($templatePath) ? file_get_contents($templatePath) : '';
        }

        // Data untuk placeholders
        $data = [
            'invoice_number'  => $invoice->invoice_number,
            'client_name'     => optional($invoice->client)->contact_name ?? '—',
            'company_name'    => optional($invoice->client)->company_name ?? '-',
            'client_phone'    => optional($invoice->client)->phone ?? '-',
            'client_instagram'=> optional($invoice->client)->instagram ?? '-',
            'client_address'  => optional($invoice->client)->address ?? '-',
            'project_name'    => $invoice->project?->name ?? '—',
            'invoice_date'    => $invoice->invoice_date->format('d M Y'),
            'session_date'    => $invoice->session_date?->format('d M Y') ?? '-',
            'due_date'        => $invoice->due_date?->format('d M Y') ?? '-',
            'bank_name'       => $invoice->bank_name ?? 'BCA',
            'bank_account'    => $invoice->bank_account ?? '0191040839',
            'bank_holder'     => $invoice->bank_holder ?? 'PT JALUR TENGAH KREASINDO',
            'payment_notes'   => $invoice->payment_notes ?? '',
        ];

        // Render items table sebagai HTML
        $itemsHtml = $this->renderItemsTable($invoice);
        $summaryHtml = $this->renderSummaryTable($invoice);

        // Masukkan HTML ke markdown
        $markdown = str_replace(
            ['{{items_table}}', '{{summary_table}}'],
            [$itemsHtml, $summaryHtml],
            $markdown
        );

        // Parse Markdown ke HTML
        $contentHtml = MarkdownHelper::parse($markdown, $data);

        $pdf = Pdf::loadView('admin.invoices.pdf-markdown', [
            'invoice'     => $invoice,
            'contentHtml' => $contentHtml,
        ]);

        $filename = str_replace('/', '-', $invoice->invoice_number) . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Render tabel items sebagai HTML untuk disisipkan ke Markdown.
     */
    protected function renderItemsTable(Invoice $invoice): string
    {
        $html = '<table>';
        $html .= '<tr><th>Layanan</th><th>Deskripsi</th><th style="text-align:center;">Harga</th><th style="text-align:center;">Disc</th><th style="text-align:center;">Total</th></tr>';

        foreach ($invoice->items as $index => $item) {
            $rowClass = $index % 2 === 1 ? ' class="row-alt"' : '';
            $html .= '<tr' . $rowClass . '>';
            $html .= '<td><strong>' . e($item->service_name) . '</strong></td>';
            $html .= '<td>' . e($item->description ?? '') . '</td>';
            $html .= '<td style="text-align:center;">Rp' . number_format($item->price, 0, ',', '.') . '</td>';
            $html .= '<td style="text-align:center;">' . ($item->disc_percent > 0 ? $item->disc_percent . '%' : '—') . '</td>';
            $html .= '<td style="text-align:center;">Rp' . number_format($item->total, 0, ',', '.') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }

    /**
     * Render tabel ringkasan sebagai HTML untuk disisipkan ke Markdown.
     */
    protected function renderSummaryTable(Invoice $invoice): string
    {
        $html = '<table>';

        $html .= '<tr><td style="text-align:right;">Subtotal</td><td style="text-align:center;">Rp' . number_format($invoice->subtotal, 0, ',', '.') . '</td></tr>';

        if ($invoice->pph_rate > 0) {
            $html .= '<tr class="summary-pph"><td style="text-align:right;">PPH ' . number_format($invoice->pph_rate, 0) . '%</td>';
            $html .= '<td style="text-align:center;">(Rp' . number_format($invoice->pph_amount, 0, ',', '.') . ')</td></tr>';
        }

        $html .= '<tr class="summary-total"><td style="text-align:right;"><strong>TOTAL</strong></td>';
        $html .= '<td style="text-align:center;"><strong>Rp' . number_format($invoice->total, 0, ',', '.') . '</strong></td></tr>';

        if ($invoice->dp_amount > 0) {
            $html .= '<tr class="summary-dp"><td style="text-align:right;">DP</td>';
            $html .= '<td style="text-align:center;">Rp' . number_format($invoice->dp_amount, 0, ',', '.') . '</td></tr>';
            $html .= '<tr class="summary-remaining"><td style="text-align:right;">Pelunasan</td>';
            $html .= '<td style="text-align:center;">Rp' . number_format($invoice->remaining, 0, ',', '.') . '</td></tr>';
        }

        $html .= '</table>';
        return $html;
    }
}