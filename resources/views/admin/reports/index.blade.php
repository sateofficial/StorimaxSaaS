@extends('layouts.app')

@section('title', 'Laporan')
@section('header', 'Laporan')
@section('subheader', 'Rekap kinerja & performa crew')

@section('content')
{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500">Total Crew</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $totalCrew }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500">Project Aktif</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $activeProjects }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500">Total Job</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $totalJobs }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500">Completion Rate</p>
        <p class="text-2xl font-semibold mt-1 {{ $completionRate >= 80 ? 'text-green-600' : ($completionRate >= 50 ? 'text-amber-600' : 'text-red-500') }}">
            {{ $completionRate }}%
        </p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500">Invoice Pending</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $pendingInvoices }}</p>
    </div>
</div>

{{-- Export Buttons --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.reports.export.pdf') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        Export PDF
    </a>
    <a href="{{ route('admin.reports.export.excel') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 text-sm font-medium rounded-lg hover:bg-green-100 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export Excel (CSV)
    </a>
</div>

{{-- Crew Performance Table --}}
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-medium text-gray-900">Performa Crew</h3>
    </div>

    @if($crewMembers->isEmpty())
        <div class="py-16 text-center text-sm text-gray-400">Belum ada crew aktif.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Crew</th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Total</th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">To Do</th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">In Progress</th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Review</th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Done</th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Rate</th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($crewMembers as $crew)
                @php
                    $rateColor = match(true) {
                        $crew->completion_rate >= 80 => 'text-green-600',
                        $crew->completion_rate >= 50 => 'text-amber-600',
                        default                       => 'text-red-500',
                    };
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-medium text-gray-600">
                                    {{ strtoupper(substr($crew->name, 0, 1)) }}
                                </span>
                            </div>
                            <span class="font-medium text-gray-900">{{ $crew->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-center font-medium">{{ $crew->jobs_count }}</td>
                    <td class="px-5 py-3 text-center text-gray-400">{{ $crew->todo_count }}</td>
                    <td class="px-5 py-3 text-center text-blue-600 font-medium">{{ $crew->inprogress_count }}</td>
                    <td class="px-5 py-3 text-center text-amber-600 font-medium">{{ $crew->review_count }}</td>
                    <td class="px-5 py-3 text-center text-green-600 font-medium">{{ $crew->done_count }}</td>
                    <td class="px-5 py-3 text-center font-semibold {{ $rateColor }}">{{ $crew->completion_rate }}%</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.reports.crew', $crew) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>


@endsection
