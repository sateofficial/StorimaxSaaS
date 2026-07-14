@extends('layouts.app')

@section('title', $invoice->invoice_number)
@section('header', $invoice->invoice_number)
@section('subheader', $invoice->project?->name ?? '—')

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
    <span class="text-xs px-3 py-1.5 rounded-full font-semibold {{ $statusColor }}">
        {{ $invoice->status->label() }}
    </span>
</div>

{{-- Invoice preview --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 max-w-3xl shadow-sm">

    {{-- Header --}}
    <div class="mb-5 pb-4 border-b-2 border-blue-600">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-lg font-bold text-blue-600 tracking-tight">STORIMAX</h1>
                <p class="text-xs text-gray-400 mt-0.5">Story in Motion. Maxed to Perfection.</p>
            </div>
            <div class="text-right">
                <p class="text-lg font-extrabold text-gray-900 tracking-wide">INVOICE</p>
                <p class="text-xs text-gray-400 mt-0.5">#{{ $invoice->invoice_number }}</p>
            </div>
        </div>
    </div>

    {{-- Info (bordered grid) --}}
    <div class="border border-gray-200 rounded-lg overflow-hidden mb-5">
        <table class="w-full text-xs divide-y divide-gray-200">
            <thead>
                <tr class="bg-blue-600">
                    <th colspan="2" class="text-left py-2 px-3 text-sm font-bold text-white uppercase tracking-wider">Informasi Invoice</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr><td class="py-1.5 px-3 text-xs font-medium text-gray-500 w-36 bg-gray-50/50">No Invoice</td><td class="py-1.5 px-3 text-xs font-medium text-gray-900">{{ $invoice->invoice_number }}</td></tr>
                <tr><td class="py-1.5 px-3 text-xs font-medium text-gray-500 bg-gray-50/50">Tgl Invoice</td><td class="py-1.5 px-3 text-xs text-gray-900">{{ $invoice->invoice_date->format('d M Y') }}</td></tr>
                <tr><td class="py-1.5 px-3 text-xs font-medium text-gray-500 bg-gray-50/50">Tgl Sesi</td><td class="py-1.5 px-3 text-xs text-gray-900">{{ $invoice->session_date?->format('d M Y') ?? '-' }}</td></tr>
                <tr><td class="py-1.5 px-3 text-xs font-medium text-gray-500 bg-gray-50/50">Project</td><td class="py-1.5 px-3 text-xs font-medium text-gray-900">{{ $invoice->project?->name ?? '—' }}</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Rincian (bordered table) --}}
    <div class="border border-gray-200 rounded-lg overflow-hidden mb-5">
        <table class="w-full text-xs">
            <thead>
                <tr class="bg-gray-900 text-white">
                    <th class="text-left py-2 px-3 text-xs font-bold uppercase tracking-wider">Layanan</th>
                    <th class="text-left py-2 px-3 text-xs font-bold uppercase tracking-wider">Deskripsi</th>
                    <th class="text-center py-2 px-3 text-xs font-bold uppercase tracking-wider">Harga</th>
                    <th class="text-center py-2 px-3 text-xs font-bold uppercase tracking-wider">Disc</th>
                    <th class="text-center py-2 px-3 text-xs font-bold uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($invoice->items as $item)
                <tr class="hover:bg-gray-50/50">
                    <td class="py-2 px-3 text-xs font-semibold text-gray-900">{{ $item->service_name }}</td>
                    <td class="py-2 px-3 text-xs text-gray-500">{{ $item->description ?? '-' }}</td>
                    <td class="py-2 px-3 text-xs text-center text-gray-700">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="py-2 px-3 text-xs text-center text-gray-500">{{ $item->disc_percent > 0 ? $item->disc_percent . '%' : '—' }}</td>
                    <td class="py-2 px-3 text-xs text-center font-bold text-gray-900">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Summary (bordered) --}}
    <div class="border border-gray-200 rounded-lg overflow-hidden mb-5">
        <table class="w-full text-xs">
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="py-1.5 px-4 text-xs font-medium text-gray-500 bg-gray-50/50 w-3/4">Subtotal</td>
                    <td class="py-1.5 px-4 text-xs text-center font-semibold text-gray-700 w-1/4">Rp{{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                </tr>
                @if($invoice->pph_rate > 0)
                <tr class="text-gray-500">
                    <td class="py-1.5 px-4 text-xs font-medium bg-gray-50/50">PPH {{ number_format($invoice->pph_rate, 0) }}%</td>
                    <td class="py-1.5 px-4 text-xs text-center">(Rp{{ number_format($invoice->pph_amount, 0, ',', '.') }})</td>
                </tr>
                @endif
                <tr class="bg-blue-50 text-blue-700">
                    <td class="py-2 px-4 text-xs font-bold border-t-2 border-blue-300">TOTAL</td>
                    <td class="py-2 px-4 text-xs text-center font-bold border-t-2 border-blue-300">Rp{{ number_format($invoice->total, 0, ',', '.') }}</td>
                </tr>
                @if($invoice->dp_amount > 0)
                <tr class="text-amber-700 bg-amber-50/50">
                    <td class="py-1.5 px-4 text-xs font-semibold">DP (Dibayar)</td>
                    <td class="py-1.5 px-4 text-xs text-center font-bold">Rp{{ number_format($invoice->dp_amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="text-emerald-700 bg-emerald-50/50">
                    <td class="py-1.5 px-4 text-xs font-semibold">Sisa Tagihan</td>
                    <td class="py-1.5 px-4 text-xs text-center font-bold">Rp{{ number_format($invoice->remaining, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pembayaran (bordered box) --}}
    <div class="border-2 border-blue-200 rounded-lg p-4 bg-blue-50/30">
        <p class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">Metode Pembayaran</p>
        <p class="text-xs text-blue-600 mb-1">Transfer ke:</p>
        <p class="text-sm font-bold text-gray-900">
            {{ $invoice->bank_name }} - {{ $invoice->bank_account }} a.n {{ $invoice->bank_holder }}
        </p>
        @if($invoice->payment_notes)
        <p class="text-xs text-gray-500 mt-2 italic leading-relaxed">{{ $invoice->payment_notes }}</p>
        @endif
    </div>
</div>

@endsection