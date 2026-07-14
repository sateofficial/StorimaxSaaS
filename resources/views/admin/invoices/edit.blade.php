@extends('layouts.app')

@section('title', 'Edit ' . $invoice->invoice_number)
@section('header', 'Edit Invoice')
@section('subheader', $invoice->invoice_number)

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.invoices.update', $invoice) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Informasi Invoice --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-900">Informasi Tanggal</h3>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tgl Invoice <span class="text-red-400">*</span></label>
                    <input type="date" name="invoice_date" value="{{ $invoice->invoice_date->format('Y-m-d') }}" required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tgl Sesi</label>
                    <input type="date" name="session_date" value="{{ $invoice->session_date?->format('Y-m-d') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Jatuh Tempo</label>
                    <input type="date" name="due_date" value="{{ $invoice->due_date?->format('Y-m-d') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
            </div>
        </div>

        {{-- Pajak & DP --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-900">Pajak & DP</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">PPH (%)</label>
                    <input type="number" name="pph_rate" value="{{ $invoice->pph_rate }}" step="0.01"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">DP (Rp)</label>
                    <input type="number" name="dp_amount" value="{{ intval($invoice->dp_amount) }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
            </div>
        </div>

        {{-- Metode Pembayaran --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-900">Metode Pembayaran</h3>
            <p class="text-xs text-gray-400 mb-2">Update informasi rekening jika ada perubahan.</p>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Bank</label>
                    <input type="text" name="bank_name" value="{{ $invoice->bank_name }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">No. Rekening</label>
                    <input type="text" name="bank_account" value="{{ $invoice->bank_account }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Atas Nama</label>
                    <input type="text" name="bank_holder" value="{{ $invoice->bank_holder }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Catatan Pembayaran</label>
                <textarea name="payment_notes" rows="3"
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none"
                >{{ $invoice->payment_notes }}</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.invoices.show', $invoice) }}"
               class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection