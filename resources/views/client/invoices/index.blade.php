@extends('layouts.app')

@section('title', 'Invoice Saya')
@section('header', 'Invoice')
@section('subheader', 'Riwayat invoice dan pembayaran')

@section('content')

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">

    @if($invoices->isEmpty())
    <div class="py-16 text-center text-sm text-gray-400">
        Belum ada invoice untuk akun kamu.
    </div>
    @else
    <div class="divide-y divide-gray-50">
        @foreach($invoices as $invoice)
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
        <div class="px-5 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3">
                    <a href="{{ route('client.invoices.show', $invoice) }}"
                       class="text-sm font-medium text-gray-900 hover:text-blue-600 transition">
                        {{ $invoice->invoice_number }}
                    </a>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $statusColor }}">
                        {{ $invoice->status->label() }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $invoice->project?->name ?? '—' }} · {{ $invoice->invoice_date->format('d M Y') }}
                </p>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-sm font-medium text-gray-900">
                    Rp {{ number_format($invoice->total, 0, ',', '.') }}
                </p>
                @if($invoice->remaining > 0 && $invoice->status->value !== 'paid')
                <p class="text-xs text-orange-500">
                    Sisa: Rp {{ number_format($invoice->remaining, 0, ',', '.') }}
                </p>
                @endif
            </div>
            <a href="{{ route('client.invoices.show', $invoice) }}"
               class="text-xs text-gray-400 hover:text-gray-700 transition flex-shrink-0">
                Detail
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
