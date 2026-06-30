@extends('layouts.app')

@section('title', $invoice->invoice_number)
@section('header', $invoice->invoice_number)
@section('subheader', $invoice->client->contact_name . ' — ' . $invoice->project->name)

@section('content')

<div class="flex items-center justify-between mb-6">
    @php
    $statusColor = match($invoice->status->value) {
        'draft'   => 'bg-gray-100 text-gray-600',
        'sent'    => 'bg-blue-50 text-blue-700',
        'dp_paid' => 'bg-yellow-50 text-yellow-700',
        'paid'    => 'bg-green-50 text-green-700',
        'overdue' => 'bg-red-50 text-red-600',
        default   => 'bg-gray-100 text-gray-600',
    };
    @endphp
    <span class="text-xs px-3 py-1.5 rounded-full font-medium {{ $statusColor }}">
        {{ $invoice->status->label() }}
    </span>

    <div class="flex items-center gap-3">
        <form method="POST" action="{{ route('admin.invoices.update-status', $invoice) }}">
            @csrf
            @method('PATCH')
            <select name="status" onchange="this.form.submit()"
                    class="text-xs px-2 py-1.5 border border-gray-200 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-gray-900">
                @foreach(['draft', 'sent', 'dp_paid', 'paid', 'overdue'] as $s)
                <option value="{{ $s }}" {{ $invoice->status->value === $s ? 'selected' : '' }}>
                    {{ match($s) { 'dp_paid' => 'DP Dibayar', default => ucfirst($s) } }}
                </option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.invoices.pdf', $invoice) }}"
           class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
            Download PDF
        </a>
    </div>
</div>

{{-- Invoice preview ala Storimax --}}
<div class="bg-white rounded-xl border border-gray-100 p-8 max-w-3xl">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold text-blue-600">STORIMAX</h2>
        <p class="text-xs text-gray-400">Story in Motion. Maxed to Perfection.</p>
    </div>

    {{-- Info client --}}
    <table class="w-full text-sm mb-6">
        <tr class="border-b border-gray-100">
            <td class="py-2 font-medium text-gray-600 w-40">Nama Client</td>
            <td class="py-2 text-gray-900">{{ $invoice->client->contact_name }}</td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="py-2 font-medium text-gray-600">Kontak</td>
            <td class="py-2 text-gray-900">{{ $invoice->client->phone ?? '-' }}</td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="py-2 font-medium text-gray-600">Akun Instagram</td>
            <td class="py-2 text-gray-900">{{ $invoice->client->instagram ?? '-' }}</td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="py-2 font-medium text-gray-600">Alamat/Instansi</td>
            <td class="py-2 text-gray-900">{{ $invoice->client->address ?? '-' }}</td>
        </tr>
        <tr>
            <td class="py-2 font-medium text-gray-600">Tgl Sesi</td>
            <td class="py-2 text-gray-900">{{ $invoice->session_date?->format('d M Y') ?? '-' }}</td>
        </tr>
    </table>

    {{-- Rincian --}}
    <table class="w-full text-sm mb-4">
        <thead>
            <tr class="bg-green-50">
                <th class="text-left py-2 px-2 font-medium text-gray-700">Jenis Layanan</th>
                <th class="text-left py-2 px-2 font-medium text-gray-700">Deskripsi</th>
                <th class="text-right py-2 px-2 font-medium text-gray-700">Harga</th>
                <th class="text-right py-2 px-2 font-medium text-gray-700">Disc</th>
                <th class="text-right py-2 px-2 font-medium text-gray-700">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr class="border-b border-gray-50">
                <td class="py-2 px-2">{{ $item->service_name }}</td>
                <td class="py-2 px-2 text-gray-500">{{ $item->description }}</td>
                <td class="py-2 px-2 text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="py-2 px-2 text-right">{{ $item->disc_percent }}%</td>
                <td class="py-2 px-2 text-right font-medium">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Summary --}}
    <table class="w-full text-sm mb-6">
        <tr class="bg-blue-50">
            <td class="py-2 px-2 font-medium">PPH {{ $invoice->pph_rate }}%</td>
            <td class="py-2 px-2 text-right">Rp{{ number_format($invoice->pph_amount, 0, ',', '.') }}</td>
        </tr>
        <tr class="bg-blue-100">
            <td class="py-2 px-2 font-bold">Total</td>
            <td class="py-2 px-2 text-right font-bold">Rp{{ number_format($invoice->total, 0, ',', '.') }}</td>
        </tr>
        <tr class="bg-yellow-50">
            <td class="py-2 px-2 font-medium">DP</td>
            <td class="py-2 px-2 text-right">Rp{{ number_format($invoice->dp_amount, 0, ',', '.') }}</td>
        </tr>
        <tr class="bg-yellow-100">
            <td class="py-2 px-2 font-bold">Pelunasan</td>
            <td class="py-2 px-2 text-right font-bold">Rp{{ number_format($invoice->remaining, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- Metode pembayaran --}}
    <div class="text-sm">
        <p class="font-medium text-gray-900 mb-2">METODE PEMBAYARAN</p>
        <p class="text-blue-600 mb-1">Transfer ke:</p>
        <p class="font-bold text-gray-900 mb-3">
            {{ $invoice->bank_name }} - {{ $invoice->bank_account }} a.n {{ $invoice->bank_holder }}
        </p>
        <p class="text-gray-500 text-xs">{{ $invoice->payment_notes }}</p>
        <p class="font-bold text-gray-900 mt-4">{{ $invoice->bank_holder }}</p>
    </div>
</div>

@endsection