<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1a1a1a; }
        h1 { color: #2563eb; font-size: 20px; margin-bottom: 0; }
        .tagline { color: #999; font-size: 10px; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        td, th { padding: 6px 8px; text-align: left; }
        .bg-green { background-color: #dcfce7; }
        .bg-blue { background-color: #dbeafe; }
        .bg-blue-dark { background-color: #bfdbfe; }
        .bg-yellow { background-color: #fef9c3; }
        .bg-yellow-dark { background-color: #fef08a; }
        .text-right { text-align: right; }
        .border-bottom { border-bottom: 1px solid #eee; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <h1>STORIMAX</h1>
    <p class="tagline">Story in Motion. Maxed to Perfection.</p>

    <table>
        <tr class="border-bottom">
            <td width="150" class="bold">Nama Client</td>
            <td>{{ $invoice->client->contact_name }}</td>
        </tr>
        <tr class="border-bottom">
            <td class="bold">Kontak</td>
            <td>{{ $invoice->client->phone ?? '-' }}</td>
        </tr>
        <tr class="border-bottom">
            <td class="bold">Akun Instagram</td>
            <td>{{ $invoice->client->instagram ?? '-' }}</td>
        </tr>
        <tr class="border-bottom">
            <td class="bold">Alamat/Instansi</td>
            <td>{{ $invoice->client->address ?? '-' }}</td>
        </tr>
        <tr>
            <td class="bold">Tgl Sesi</td>
            <td>{{ $invoice->session_date?->format('d M Y') ?? '-' }}</td>
        </tr>
    </table>

    <table>
        <tr class="bg-green bold">
            <th>Jenis Layanan</th>
            <th>Deskripsi</th>
            <th class="text-right">Harga</th>
            <th class="text-right">Disc</th>
            <th class="text-right">Total</th>
        </tr>
        @foreach($invoice->items as $item)
        <tr class="border-bottom">
            <td>{{ $item->service_name }}</td>
            <td>{{ $item->description }}</td>
            <td class="text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="text-right">{{ $item->disc_percent }}%</td>
            <td class="text-right bold">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <table>
        <tr class="bg-blue">
            <td class="bold">PPH {{ $invoice->pph_rate }}%</td>
            <td class="text-right">Rp{{ number_format($invoice->pph_amount, 0, ',', '.') }}</td>
        </tr>
        <tr class="bg-blue-dark">
            <td class="bold">Total</td>
            <td class="text-right bold">Rp{{ number_format($invoice->total, 0, ',', '.') }}</td>
        </tr>
        <tr class="bg-yellow">
            <td class="bold">DP</td>
            <td class="text-right">Rp{{ number_format($invoice->dp_amount, 0, ',', '.') }}</td>
        </tr>
        <tr class="bg-yellow-dark">
            <td class="bold">Pelunasan</td>
            <td class="text-right bold">Rp{{ number_format($invoice->remaining, 0, ',', '.') }}</td>
        </tr>
    </table>

    <p class="bold">METODE PEMBAYARAN</p>
    <p style="color: #2563eb;">Transfer ke:</p>
    <p class="bold">{{ $invoice->bank_name }} - {{ $invoice->bank_account }} a.n {{ $invoice->bank_holder }}</p>
    <p style="color: #999; font-size: 10px;">{{ $invoice->payment_notes }}</p>
    <p class="bold" style="margin-top: 16px;">{{ $invoice->bank_holder }}</p>
</body>
</html>