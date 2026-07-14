<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 16mm 14mm; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #1f2937;
            line-height: 1.55;
            margin: 0;
            padding: 0;
        }

        /* ── Layout ── */
        .invoice-container {
            border: 1.5px solid #d1d5db;
            padding: 18px 20px;
        }

        /* ── Header ── */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0;
        }
        .invoice-header .brand {
            font-size: 16px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: 1px;
            margin: 0;
        }
        .invoice-header .tagline {
            font-size: 7.5px;
            color: #9ca3af;
            margin: 1px 0 0 0;
        }
        .invoice-header .title-area {
            text-align: right;
        }
        .invoice-header .title-area h1 {
            font-size: 20px;
            font-weight: 900;
            color: #2563eb;
            letter-spacing: 1.5px;
            margin: 0;
        }
        .invoice-header .title-area .number {
            font-size: 8px;
            color: #9ca3af;
            margin: 2px 0 0 0;
        }
        .divider {
            border: none;
            border-top: 2px solid #2563eb;
            margin: 10px 0 13px 0;
        }

        /* ── Markdown Content ── */
        .md-content h1 {
            font-size: 11px;
            font-weight: 700;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 10px 0 4px 0;
            padding: 3px 6px;
            background: #2563eb;
            color: #fff;
        }
        .md-content h2 {
            font-size: 9.5px;
            font-weight: 700;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 8px 0 3px 0;
            padding: 3px 6px;
            background: #2563eb;
            color: #fff;
        }
        .md-content h3 { font-size: 9px; font-weight: 700; color: #111827; margin: 6px 0 2px 0; }
        .md-content h4, .md-content h5 { font-size: 8.5px; font-weight: 600; margin: 4px 0 2px 0; }
        .md-content p { margin: 0 0 4px 0; font-size: 8.5px; }
        .md-content p:first-child { margin-top: 0; }
        .md-content hr {
            border: none;
            border-top: 1px solid #d1d5db;
            margin: 8px 0;
        }

        /* ── Tables ── */
        .md-content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border: 1px solid #d1d5db;
            font-size: 8.5px;
        }
        .md-content table th {
            background: #2563eb;
            color: #fff;
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 4px 6px;
            border: 1px solid #1d4ed8;
            text-align: left;
        }
        .md-content table td {
            padding: 3px 6px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .md-content table tr.row-alt td {
            background: #f9fafb;
        }

        /* ── Inline ── */
        .md-content strong { color: #111827; }
        .md-content em { color: #6b7280; font-style: italic; }

        /* ── Summary highlight ── */
        .md-content .summary-total td {
            background: #eff6ff !important;
            font-weight: 800;
            color: #2563eb;
            font-size: 10px;
            border-color: #bfdbfe;
        }
        .md-content .summary-dp td {
            color: #d97706;
            background: #fffbeb !important;
            font-weight: 600;
        }
        .md-content .summary-remaining td {
            color: #059669;
            background: #ecfdf5 !important;
            font-weight: 700;
        }
        .md-content .summary-pph td {
            color: #9ca3af;
        }
        .md-content .value-right { text-align: right; }

        /* ── Payment Box ── */
        .payment-box {
            border: 1.5px solid #2563eb;
            padding: 8px 12px;
            margin: 10px 0 0 0;
        }
        .payment-box p { margin: 2px 0; font-size: 8.5px; }

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
    </style>
</head>
<body>

<div class="invoice-container">

    {{-- Header --}}
    <div class="invoice-header">
        <div>
            <p class="brand">STORIMAX</p>
            <p class="tagline">Story in Motion. Maxed to Perfection.</p>
        </div>
        <div class="title-area">
            <h1>INVOICE</h1>
            <p class="number">#{{ $invoice->invoice_number }}</p>
        </div>
    </div>

    <hr class="divider">

    {{-- Markdown Content --}}
    <div class="md-content">
        {!! $contentHtml !!}
    </div>

    {{-- Payment Box --}}
    <div class="payment-box">
        <p><strong>Metode Pembayaran</strong></p>
        <p>
            {{ $invoice->bank_name ?? 'BCA' }} &mdash;
            {{ $invoice->bank_account ?? '0191040839' }}
            a.n {{ $invoice->bank_holder ?? 'PT JALUR TENGAH KREASINDO' }}
        </p>
        @if($invoice->payment_notes)
        <p><em>{{ $invoice->payment_notes }}</em></p>
        @endif
    </div>

    <div class="footer">
        Storimax &mdash; PT Jalur Tengah Kreasindo &mdash; Dicetak {{ now()->format('d M Y H:i') }}
    </div>

</div>

</body>
</html>