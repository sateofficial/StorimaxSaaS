<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 16mm 14mm; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9.5px;
            color: #1f2937;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* ── Invoice Container ── */
        .invoice-container {
            border: 1.5px solid #d1d5db;
            padding: 18px 20px;
        }

        /* ── Header with Logo ── */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
        .header-table td { vertical-align: middle; padding: 0; }
        .header-table .logo-img { width: 120px; height: auto; }
        .header-table .invoice-title {
            font-size: 20px;
            font-weight: 900;
            color: #2563eb;
            text-align: right;
            letter-spacing: 1.5px;
        }
        .header-table .invoice-number {
            font-size: 8px;
            color: #9ca3af;
            text-align: right;
            margin-top: 2px;
        }
        .header-table .tagline {
            font-size: 7.5px;
            color: #9ca3af;
            margin-top: 1px;
        }

        /* ── Blue divider ── */
        .divider {
            border-top: 2px solid #2563eb;
            margin: 10px 0 13px 0;
        }

        /* ── Client & Project Info ── */
        .info-grid { width: 100%; border-collapse: collapse; }
        .info-grid td {
            padding: 3px 7px;
            vertical-align: top;
            border: 1px solid #e5e7eb;
        }
        .info-grid .cell-label {
            width: 80px;
            font-weight: 600;
            color: #6b7280;
            font-size: 8.5px;
            background: #f9fafb;
        }
        .info-grid .cell-value {
            color: #111827;
            font-size: 9px;
            font-weight: 400;
        }
        .info-grid .section-header td {
            background: #2563eb;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 4px 8px;
            border: 1px solid #1d4ed8;
        }

        /* ── Items Table ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border: 1px solid #d1d5db;
        }
        .items-table thead th {
            background: #2563eb;
            color: #fff;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 5px 8px;
            text-align: left;
            border: 1px solid #1d4ed8;
        }
        .items-table thead th.center { text-align: center; }
        .items-table tbody td {
            padding: 5px 8px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 8.5px;
        }
        .items-table tbody tr.row-alt td {
            background: #f9fafb;
        }
        .items-table .service-name { font-weight: 700; font-size: 9px; color: #111827; }
        .items-table .description { font-size: 7.5px; color: #9ca3af; margin-top: 1px; }
        .items-table .center { text-align: center; }

        /* ── Summary Table ── */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1px solid #d1d5db;
        }
        .summary-table td {
            padding: 4px 10px;
            font-size: 9px;
            border: 1px solid #e5e7eb;
        }
        .summary-table .label-col {
            font-weight: 500;
            color: #6b7280;
            text-align: right;
            width: 75%;
            background: #f9fafb;
        }
        .summary-table .value-col {
            font-weight: 600;
            width: 25%;
            text-align: center;
        }
        .summary-table .total-row td {
            background: #eff6ff;
            font-weight: 800;
            color: #2563eb;
            font-size: 11px;
            border: 1px solid #bfdbfe;
        }
        .summary-table .total-row .label-col { font-size: 10px; }
        .summary-table .total-row .value-col { font-size: 12px; }
        .summary-table .pph-row td { font-size: 8.5px; }
        .summary-table .pph-row .value-col { color: #9ca3af; }
        .summary-table .dp-row td { color: #d97706; font-weight: 600; font-size: 9px; background: #fffbeb; }
        .summary-table .remaining-row td { color: #059669; font-weight: 700; font-size: 9px; background: #ecfdf5; }

        /* ── Payment ── */
        .payment-box {
            border: 1.5px solid #2563eb;
            padding: 10px 14px;
            margin-top: 2px;
        }
        .payment-box .title {
            font-size: 9px;
            font-weight: 700;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin: 0 0 5px 0;
        }
        .payment-box .bank-info {
            font-size: 9px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }
        .payment-box .notes {
            font-size: 8px;
            color: #9ca3af;
            margin: 6px 0 0 0;
            font-style: italic;
        }

        /* ── Footer ── */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }

        /* ── Helper ── */
        .text-xs { font-size: 7.5px; color: #9ca3af; }
        .mt-1 { margin-top: 3px; }
        .mb-1 { margin-bottom: 3px; }
    </style>
</head>
<body>

<div class="invoice-container">

    {{-- Header with Logo --}}
    <table class="header-table">
        <tr>
            <td width="45%">
                @if(file_exists(public_path('images/logo.png')))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Storimax" class="logo-img">
                @else
                <span style="font-size:16px;font-weight:800;color:#2563eb;letter-spacing:1px;">STORIMAX</span>
                @endif
                <div class="tagline">Story in Motion. Maxed to Perfection.</div>
            </td>
            <td width="55%">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- Client & Project Info (bordered grid) --}}
    <table class="info-grid">
        <tr class="section-header"><td colspan="2">Kepada</td></tr>
        <tr><td class="cell-label">Client</td><td class="cell-value">{{ optional($invoice->client)->contact_name ?? '—' }}</td></tr>
        @if($invoice->client && $invoice->client->company_name)
        <tr><td class="cell-label">Perusahaan</td><td class="cell-value">{{ $invoice->client->company_name }}</td></tr>
        @endif
        <tr><td class="cell-label">Kontak</td><td class="cell-value">{{ optional($invoice->client)->phone ?? '-' }}</td></tr>
        @if($invoice->client && $invoice->client->instagram)
        <tr><td class="cell-label">Instagram</td><td class="cell-value">{{ $invoice->client->instagram }}</td></tr>
        @endif
        @if($invoice->client && $invoice->client->address)
        <tr><td class="cell-label">Alamat</td><td class="cell-value">{{ $invoice->client->address }}</td></tr>
        @endif

        <tr class="section-header"><td colspan="2">Detail Project</td></tr>
        <tr><td class="cell-label">Project</td><td class="cell-value">{{ $invoice->project?->name ?? '—' }}</td></tr>
        <tr><td class="cell-label">Tgl Invoice</td><td class="cell-value">{{ $invoice->invoice_date->format('d M Y') }}</td></tr>
        @if($invoice->session_date)
        <tr><td class="cell-label">Tgl Sesi</td><td class="cell-value">{{ $invoice->session_date->format('d M Y') }}</td></tr>
        @endif
        @if($invoice->due_date)
        <tr><td class="cell-label">Jatuh Tempo</td><td class="cell-value">{{ $invoice->due_date->format('d M Y') }}</td></tr>
        @endif
    </table>

    <div style="margin-bottom:10px;"></div>

    {{-- Items Table (bordered) --}}
    <table class="items-table">
        <thead>
            <tr>
                <th width="30%">Layanan</th>
                <th width="30%">Deskripsi</th>
                <th width="15%" class="center">Harga</th>
                <th width="10%" class="center">Diskon</th>
                <th width="15%" class="center">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice->items as $item)
            <tr @if($loop->even) class="row-alt" @endif>
                <td><div class="service-name">{{ $item->service_name }}</div></td>
                <td>@if($item->description)<div class="description">{{ $item->description }}</div>@endif</td>
                <td class="center">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="center">{{ $item->disc_percent > 0 ? $item->disc_percent . '%' : '—' }}</td>
                <td class="center">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#9ca3af;padding:10px;border:1px solid #e5e7eb;">Tidak ada item.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Summary (bordered) --}}
    <table class="summary-table">
        <tr><td class="label-col">Subtotal</td><td class="value-col">Rp{{ number_format($invoice->subtotal, 0, ',', '.') }}</td></tr>
        @if($invoice->pph_rate > 0)
        <tr class="pph-row"><td class="label-col">PPH {{ number_format($invoice->pph_rate, 0) }}%</td><td class="value-col">(Rp{{ number_format($invoice->pph_amount, 0, ',', '.') }})</td></tr>
        @endif
        <tr class="total-row"><td class="label-col">TOTAL</td><td class="value-col">Rp{{ number_format($invoice->total, 0, ',', '.') }}</td></tr>
        @if($invoice->dp_amount > 0)
        <tr class="dp-row"><td class="label-col">DP</td><td class="value-col">Rp{{ number_format($invoice->dp_amount, 0, ',', '.') }}</td></tr>
        <tr class="remaining-row"><td class="label-col">Pelunasan</td><td class="value-col">Rp{{ number_format($invoice->remaining, 0, ',', '.') }}</td></tr>
        @endif
    </table>

    {{-- Payment Method --}}
    <div class="payment-box">
        <div class="title">Metode Pembayaran</div>
        <p class="bank-info">
            {{ $invoice->bank_name ?? 'BCA' }} &mdash; {{ $invoice->bank_account ?? '0191040839' }}<br>
            a.n {{ $invoice->bank_holder ?? 'PT JALUR TENGAH KREASINDO' }}
        </p>
        @if($invoice->payment_notes)
        <p class="notes">{{ $invoice->payment_notes }}</p>
        @endif
    </div>

    <div class="footer">
        Storimax &mdash; PT Jalur Tengah Kreasindo &mdash; Dicetak {{ now()->format('d M Y H:i') }}
    </div>

</div>

</body>
</html>