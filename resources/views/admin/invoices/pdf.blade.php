<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 18mm 15mm; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
            line-height: 1.5;
        }

        /* ── Section separators ── */
        .sep {
            border: none;
            height: 1px;
            background: #e0e0e0;
            margin: 14px 0;
        }
        .sep-thick {
            border: none;
            height: 2.5px;
            background: #2563eb;
            margin: 0 0 16px 0;
        }
        .sep-light {
            border: none;
            height: 1px;
            background: #eee;
            margin: 10px 0;
        }

        /* ── Header with Logo ── */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
        .header-table td { vertical-align: middle; }
        .header-table .logo-img { width: 130px; height: auto; }
        .header-table .invoice-title {
            font-size: 18px;
            font-weight: 800;
            color: #2563eb;
            text-align: right;
            letter-spacing: 0.5px;
        }
        .header-table .invoice-number {
            font-size: 10px;
            color: #888;
            text-align: right;
            margin-top: 2px;
        }
        .header-table .tagline {
            font-size: 8px;
            color: #aaa;
            margin-top: 1px;
        }

        /* ── Client & Project Info ── */
        .info-section { margin-bottom: 14px; }
        .info-section .section-label {
            font-size: 8px;
            font-weight: 700;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 2.5px 0; vertical-align: top; }
        .info-table .label {
            width: 90px;
            font-weight: 600;
            color: #666;
            font-size: 8.5px;
        }
        .info-table .value {
            color: #222;
            font-size: 9px;
        }

        /* ── Items Table ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .items-table thead th {
            background: #2563eb;
            color: #fff;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 7px 8px;
            text-align: left;
        }
        .items-table thead th.right { text-align: right; }
        .items-table tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #e8e8e8;
            vertical-align: top;
            font-size: 9px;
        }
        .items-table tbody tr:last-child td { border-bottom: none; }
        .items-table .service-name { font-weight: 600; font-size: 9.5px; }
        .items-table .description { font-size: 8px; color: #999; margin-top: 1px; }
        .items-table .right { text-align: right; }

        /* ── Summary ── */
        .summary-section { margin-bottom: 14px; }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 4px 0;
            font-size: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .summary-table .label-col {
            font-weight: 500;
            color: #555;
            text-align: right;
            width: 75%;
        }
        .summary-table .value-col {
            font-weight: 700;
            text-align: right;
            width: 25%;
        }
        .summary-table tr:last-child td {
            border-bottom: none;
        }
        .summary-table .total-row .label-col {
            font-size: 12px;
            font-weight: 800;
            color: #2563eb;
            padding-top: 8px;
            border-top: 2.5px solid #2563eb;
        }
        .summary-table .total-row .value-col {
            font-size: 13px;
            font-weight: 800;
            color: #2563eb;
            padding-top: 8px;
            border-top: 2.5px solid #2563eb;
        }
        .summary-table .pph-row .value-col { color: #999; }
        .summary-table .dp-row td { color: #d97706; }
        .summary-table .dp-row .value-col { font-weight: 700; }
        .summary-table .remaining-row td { color: #059669; font-weight: 700; }
        .summary-table .remaining-row .value-col { font-weight: 800; }

        /* ── Payment ── */
        .payment-section { }
        .payment-section .title {
            font-size: 9px;
            font-weight: 700;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 6px 0;
        }
        .payment-section .bank-box {
            background: #f8f9fc;
            border: 1px solid #e8e8e8;
            padding: 10px 12px;
            margin-top: 4px;
        }
        .payment-section .bank-info {
            font-size: 9.5px;
            font-weight: 700;
            margin: 0;
        }
        .payment-section .notes {
            font-size: 8.5px;
            color: #999;
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
            font-size: 7.5px;
            color: #bbb;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    {{-- Header with Logo --}}
    <table class="header-table">
        <tr>
            <td width="45%">
                @if(file_exists(public_path('images/logo.png')))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Storimax" class="logo-img">
                @else
                <span style="font-size:20px;font-weight:800;color:#2563eb;letter-spacing:1px;">STORIMAX</span>
                @endif
                <div class="tagline">Story in Motion. Maxed to Perfection.</div>
            </td>
            <td width="55%">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
            </td>
        </tr>
    </table>

    <hr class="sep-thick">

    {{-- Client Info --}}
    <div class="info-section">
        <div class="section-label">Kepada</div>
        <table class="info-table">
            <tr><td class="label">Client</td><td class="value">{{ $invoice->client->contact_name }}</td></tr>
            @if($invoice->client->company_name)
            <tr><td class="label">Perusahaan</td><td class="value">{{ $invoice->client->company_name }}</td></tr>
            @endif
            <tr><td class="label">Kontak</td><td class="value">{{ $invoice->client->phone ?? '-' }}</td></tr>
            @if($invoice->client->instagram)
            <tr><td class="label">Instagram</td><td class="value">{{ $invoice->client->instagram }}</td></tr>
            @endif
            @if($invoice->client->address)
            <tr><td class="label">Alamat</td><td class="value">{{ $invoice->client->address }}</td></tr>
            @endif
        </table>
    </div>

    <hr class="sep">

    {{-- Project Info --}}
    <div class="info-section">
        <div class="section-label">Detail Project</div>
        <table class="info-table">
            <tr><td class="label">Project</td><td class="value">{{ $invoice->project->name }}</td></tr>
            <tr><td class="label">Tgl Invoice</td><td class="value">{{ $invoice->invoice_date->format('d M Y') }}</td></tr>
            @if($invoice->session_date)
            <tr><td class="label">Tgl Sesi</td><td class="value">{{ $invoice->session_date->format('d M Y') }}</td></tr>
            @endif
            @if($invoice->due_date)
            <tr><td class="label">Jatuh Tempo</td><td class="value">{{ $invoice->due_date->format('d M Y') }}</td></tr>
            @endif
        </table>
    </div>

    <hr class="sep">

    {{-- Items Table --}}
    <table class="items-table">
        <thead>
            <tr>
                <th width="30%">Layanan</th>
                <th width="30%">Deskripsi</th>
                <th width="15%" class="right">Harga</th>
                <th width="10%" class="right">Diskon</th>
                <th width="15%" class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice->items as $item)
            <tr>
                <td><div class="service-name">{{ $item->service_name }}</div></td>
                <td>@if($item->description)<div class="description">{{ $item->description }}</div>@endif</td>
                <td class="right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="right">{{ $item->disc_percent > 0 ? $item->disc_percent . '%' : '—' }}</td>
                <td class="right">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#999;padding:14px;">Tidak ada item.</td></tr>
            @endforelse
        </tbody>
    </table>

    <hr class="sep-light">

    {{-- Summary --}}
    <div class="summary-section">
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
    </div>

    <hr class="sep">

    {{-- Payment Method --}}
    <div class="payment-section">
        <div class="title">Metode Pembayaran</div>
        <div class="bank-box">
            <p class="bank-info">
                {{ $invoice->bank_name ?? 'BCA' }} &mdash; {{ $invoice->bank_account ?? '0191040839' }}<br>
                a.n {{ $invoice->bank_holder ?? 'PT JALUR TENGAH KREASINDO' }}
            </p>
            @if($invoice->payment_notes)
            <p class="notes">{{ $invoice->payment_notes }}</p>
            @endif
        </div>
    </div>

    <hr class="sep-light">

    <div class="footer">
        Storimax &mdash; PT Jalur Tengah Kreasindo &mdash; Dicetak {{ now()->format('d M Y H:i') }}
    </div>

</body>
</html>