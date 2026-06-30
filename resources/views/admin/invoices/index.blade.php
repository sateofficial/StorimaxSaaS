@extends('layouts.app')

@section('title', 'Invoice')
@section('header', 'Invoice')
@section('subheader', 'Kelola invoice dan pembayaran client')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        @foreach(['', 'draft', 'sent', 'dp_paid', 'paid', 'overdue'] as $s)
        <a href="{{ request()->fullUrlWithQuery(['status' => $s]) }}"
           class="text-xs px-3 py-1.5 rounded-full transition
                  {{ request('status', '') === $s
                      ? 'bg-gray-900 text-white'
                      : 'bg-white border border-gray-200 text-gray-500 hover:border-gray-400' }}">
            {{ $s === '' ? 'Semua' : match($s) {
                'dp_paid' => 'DP Dibayar',
                default   => ucfirst($s)
            } }}
        </a>
        @endforeach
    </div>
    <a href="{{ route('admin.invoices.create') }}"
       class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
        + Buat Invoice
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">

    @php
    $filtered = request('status')
        ? $invoices->filter(fn($i) => $i->status->value === request('status'))
        : $invoices;
    @endphp

    @if($filtered->isEmpty())
    <div class="py-16 text-center text-sm text-gray-400">
        Tidak ada invoice ditemukan.
    </div>
    @else
    <div class="divide-y divide-gray-50">
        @foreach($filtered as $invoice)
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
                    <a href="{{ route('admin.invoices.show', $invoice) }}"
                       class="text-sm font-medium text-gray-900 hover:text-blue-600 transition">
                        {{ $invoice->invoice_number }}
                    </a>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $statusColor }}">
                        {{ $invoice->status->label() }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $invoice->client->contact_name }} · {{ $invoice->project->name }}
                    · {{ $invoice->invoice_date->format('d M Y') }}
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
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('admin.invoices.pdf', $invoice) }}"
                   class="text-xs text-gray-400 hover:text-gray-700 transition">PDF</a>
                <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}"
                      onsubmit="return confirm('Hapus invoice ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection