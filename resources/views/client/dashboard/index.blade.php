@extends('layouts.app')

@section('title', 'Dashboard Client')
@section('header', 'Dashboard')
@section('subheader', 'Selamat datang di Client Portal Storimax')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Project</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $totalProjects }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Invoice</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $totalInvoices }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Terbayar</p>
        <p class="text-2xl font-semibold text-green-600 mt-1">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Portofolio Publik</p>
        <p class="text-2xl font-semibold text-blue-600 mt-1">{{ $totalPortfolios }}</p>
    </div>
</div>

{{-- Recent Invoices --}}
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-medium text-gray-900">Invoice Terbaru</h3>
        <a href="{{ route('client.invoices.index') }}"
           class="text-xs text-gray-400 hover:text-gray-700 transition">
            Lihat Semua
        </a>
    </div>

    @if($recentInvoices->isEmpty())
    <div class="py-10 text-center text-sm text-gray-400">
        Belum ada invoice untuk akun kamu.
    </div>
    @else
    <div class="divide-y divide-gray-50">
        @foreach($recentInvoices as $invoice)
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
        <div class="px-5 py-3 flex items-center justify-between gap-3 hover:bg-gray-50 transition">
            <div class="flex-1 min-w-0">
                <a href="{{ route('client.invoices.show', $invoice) }}"
                   class="text-sm font-medium text-gray-900 hover:text-blue-600 transition">
                    {{ $invoice->invoice_number }}
                </a>
                <p class="text-xs text-gray-400 mt-0.5">{{ $invoice->project->name }}</p>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-sm font-medium text-gray-900">
                    Rp {{ number_format($invoice->total, 0, ',', '.') }}
                </p>
                <span class="text-xs px-1.5 py-0.5 rounded-full font-medium {{ $statusColor }}">
                    {{ $invoice->status->label() }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<div class="mt-6">
    <a href="{{ route('client.portfolios.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2z"/>
        </svg>
        Lihat Portofolio
    </a>
</div>

@endsection