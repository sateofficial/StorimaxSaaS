@extends('layouts.app')

@section('title', $invoice->invoice_number)
@section('header', $invoice->invoice_number)
@section('subheader', $invoice->project->name)

@section('content')

{{-- Status --}}
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
</div>

{{-- Invoice preview --}}
<div class="bg-white rounded-xl border border-gray-100 p-8 max-w-3xl">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold text-blue-600">STORIMAX</h2>
        <p class="text-xs text-gray-400">Story in Motion. Maxed to Perfection.</p>
    </div>

    {{-- Info --}}
    <table class="w-full text-sm mb-6">
        <tr class="border-b border-gray-100">
            <td class="py-2 font-medium text-gray-600 w-40">No Invoice</td>
            <td class="py-2 text-gray-900">{{ $invoice->invoice_number }}</td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="py-2 font-medium text-gray-600">Tgl Invoice</td>
            <td class="py-2 text-gray-900">{{ $invoice->invoice_date->format('d M Y') }}</td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="py-2 font-medium text-gray-600">Tgl Sesi</td>
            <td class="py-2 text-gray-900">{{ $invoice->session_date?->format('d M Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="py-2 font-medium text-gray-600">Project</td>
            <td class="py-2 text-gray-900">{{ $invoice->project->name }}</td>
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

    {{-- Pembayaran --}}
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
