@extends('layouts.app')

@section('title', 'Laporan - ' . $user->name)
@section('header', 'Laporan Crew')
@section('subheader', $user->name)

@section('content')
{{-- Back --}}
<a href="{{ route('admin.reports.index') }}"
   class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-4 transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
    </svg>
    Kembali ke Laporan
</a>

{{-- Crew Info --}}
<div class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
            <span class="text-lg font-semibold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h2>
            <p class="text-sm text-gray-400">{{ $user->email }}</p>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Total Job</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-semibold text-gray-400">{{ $stats['todo'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">To Do</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-semibold text-blue-600">{{ $stats['inprogress'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">In Progress</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-semibold text-amber-600">{{ $stats['review'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Review</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-semibold text-green-600">{{ $stats['done'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Done</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-semibold {{ $stats['rate'] >= 80 ? 'text-green-600' : ($stats['rate'] >= 50 ? 'text-amber-600' : 'text-red-500') }}">
            {{ $stats['rate'] }}%
        </p>
        <p class="text-xs text-gray-500 mt-0.5">Completion</p>
    </div>
</div>

{{-- Progress Bar --}}
<div class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-medium text-gray-700">Progress Keseluruhan</span>
        <span class="text-sm font-semibold {{ $stats['rate'] >= 80 ? 'text-green-600' : ($stats['rate'] >= 50 ? 'text-amber-600' : 'text-red-500') }}">
            {{ $stats['rate'] }}%
        </span>
    </div>
    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
        @php
            $barColor = match(true) {
                $stats['rate'] >= 80 => 'bg-green-500',
                $stats['rate'] >= 50 => 'bg-amber-500',
                default               => 'bg-red-500',
            };
        @endphp
        <div class="h-full rounded-full transition-all duration-500 {{ $barColor }}"
             style="width: {{ $stats['rate'] }}%"></div>
    </div>
    @if($stats['overdue'] > 0)
        <p class="mt-2 text-xs text-red-500 font-medium">{{ $stats['overdue'] }} job terlambat</p>
    @endif
</div>

{{-- Job List --}}
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-medium text-gray-900">Riwayat Job</h3>
    </div>

    @if($jobs->isEmpty())
        <div class="py-12 text-center text-sm text-gray-400">Belum ada job yang diassign.</div>
    @else
    <div class="divide-y divide-gray-50">
        @foreach($jobs as $job)
        @php
            $statusColor = match($job->status->value) {
                'todo'       => 'bg-gray-100 text-gray-600',
                'inprogress' => 'bg-blue-50 text-blue-700',
                'review'     => 'bg-yellow-50 text-yellow-700',
                'done'       => 'bg-green-50 text-green-700',
                default      => 'bg-gray-100 text-gray-600',
            };
            $priorityIcon = match($job->priority->value) {
                'urgent' => '🔴',
                'high'   => '🟠',
                'medium' => '🟡',
                'low'    => '🟢',
                default  => '⚪',
            };
        @endphp
        <div class="px-5 py-3.5 hover:bg-gray-50 transition">
            <div class="flex items-center justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span>{{ $priorityIcon }}</span>
                        <span class="text-sm font-medium text-gray-900 truncate">{{ $job->title }}</span>
                        <span class="text-xs px-1.5 py-0.5 rounded-full font-medium {{ $statusColor }}">
                            {{ $job->status->label() }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 mt-0.5">
                        <span class="text-xs text-gray-400">{{ $job->project?->name ?? '—' }}</span>
                        @if($job->deadline)
                            <span class="text-xs {{ $job->isOverdue() ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                                Deadline: {{ $job->deadline->format('d M Y') }}
                            </span>
                        @endif
                        @if($job->started_at)
                            <span class="text-xs text-gray-400">Mulai: {{ $job->started_at->format('d M Y') }}</span>
                        @endif
                        @if($job->completed_at)
                            <span class="text-xs text-green-600">Selesai: {{ $job->completed_at->format('d M Y') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
