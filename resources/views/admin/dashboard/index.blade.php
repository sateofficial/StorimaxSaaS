@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subheader', 'Selamat datang kembali, ' . auth()->user()->name)

@section('content')
{{-- Stat Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Projects</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $totalProjects }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Jobs Aktif</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $activeJobs }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Invoice Pending</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $pendingInvoices }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Crew</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $totalCrew }}</p>
    </div>

</div>

{{-- Project Progress --}}
<div class="bg-white rounded-xl border border-gray-100">
    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
        <div class="flex items-center gap-3">                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-900">Progress Project</h2>
                <p class="text-xs text-gray-400">{{ $projects->count() }} project aktif</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            {{-- Sort Toggles --}}
            <div class="flex items-center bg-gray-100 rounded-lg p-0.5 text-xs">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'progress_desc']) }}"
                   class="px-2.5 py-1.5 rounded-md transition-colors @if($sort === 'progress_desc') bg-white text-gray-900 shadow-sm font-medium @else text-gray-500 hover:text-gray-700 @endif">
                    Tertinggi
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'progress_asc']) }}"
                   class="px-2.5 py-1.5 rounded-md transition-colors @if($sort === 'progress_asc') bg-white text-gray-900 shadow-sm font-medium @else text-gray-500 hover:text-gray-700 @endif">
                    Terendah
                </a>
            </div>
            <a href="{{ route('admin.projects.index') }}"
               class="text-sm text-blue-600 hover:text-blue-800 font-medium ml-1">
                Lihat Semua &rarr;
            </a>
        </div>
    </div>

    @if($projects->count() > 0)
        <div class="divide-y divide-gray-50">
            @foreach($projects as $project)
                @php
                    $barColor = match(true) {
                        $project->progress >= 100 => 'bg-green-500',
                        $project->progress >= 60  => 'bg-emerald-500',
                        $project->progress >= 30  => 'bg-amber-500',
                        default                   => 'bg-blue-500',
                    };

                    $daysRemaining = $project->deadline
                        ? (int) now()->diffInDays($project->deadline, false)
                        : null;

                    $statusColor = match($project->status->value) {
                        'active'   => 'bg-blue-50 text-blue-700',
                        'review'   => 'bg-yellow-50 text-yellow-700',
                        'done'     => 'bg-green-50 text-green-700',
                        'draft'    => 'bg-gray-100 text-gray-600',
                        'archived' => 'bg-red-50 text-red-600',
                        default    => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <a href="{{ route('admin.projects.show', $project) }}"
                   class="block px-5 py-4 hover:bg-gray-50/80 transition-colors group">
                    {{-- Row 1: Project name + status + percentage --}}
                    <div class="flex items-start justify-between gap-4 mb-2.5">
                        <div class="flex items-center gap-2.5 min-w-0 flex-1">
                            {{-- Project Icon --}}
                            <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-50 transition-colors">
                                <svg class="w-3.5 h-3.5 text-gray-500 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors block truncate">
                                    {{ $project->name }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ $project->client?->company ?? $project->client?->contact_name ?? '—' }}
                                </span>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium flex-shrink-0 {{ $statusColor }}">
                                {{ $project->status->label() }}
                            </span>
                            @if($project->deadline)
                                @php
                                    $isOverdue = $daysRemaining < 0 && $project->status->value !== 'done';
                                    $isDueToday = $daysRemaining === 0;

                                    $deadlineColor = match(true) {
                                        $isOverdue            => 'text-red-600',
                                        $isDueToday           => 'text-amber-600',
                                        $daysRemaining <= 3   => 'text-amber-600',
                                        default               => 'text-gray-400',
                                    };
                                    $deadlineIcon = match(true) {
                                        $isOverdue            => 'M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                        $isDueToday           => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                        default               => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                    };
                                    $deadlineLabel = match(true) {
                                        $isOverdue            => 'Telat ' . abs($daysRemaining) . ' hr',
                                        $isDueToday           => 'Deadline hari ini',
                                        $daysRemaining === 1  => 'Sisa 1 hari',
                                        default               => 'Sisa ' . $daysRemaining . ' hr',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium {{ $deadlineColor }} flex-shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $deadlineIcon }}"/>
                                    </svg>
                                    {{ $deadlineLabel }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-xs text-gray-400">
                                {{ $project->done_jobs_count }}/{{ $project->jobs_count }} job
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-sm font-bold tracking-tight {{ $project->progress >= 100 ? 'text-green-600' : ($project->progress >= 60 ? 'text-emerald-600' : ($project->progress >= 30 ? 'text-amber-600' : 'text-gray-700')) }}">
                                @if($project->progress > 0)
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                @endif
                                {{ $project->progress }}%
                            </span>
                        </div>
                    </div>
                    {{-- Progress Bar --}}
                    <div class="flex items-center gap-3">
                        <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700 ease-out {{ $barColor }}"
                                 style="width: {{ $project->progress }}%"></div>
                        </div>
                        @if($project->code)
                            <span class="text-[11px] text-gray-400 font-mono flex-shrink-0">{{ $project->code }}</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-14 h-14 bg-gray-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Belum ada project aktif</p>
            <p class="text-xs text-gray-400 mt-1">Buat project baru untuk mulai memantau progress.</p>
        </div>
    @endif
</div>
@endsection