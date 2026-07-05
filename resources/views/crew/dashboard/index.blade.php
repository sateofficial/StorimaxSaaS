@extends('layouts.app')

@section('title', 'Dashboard Crew')
@section('header', 'Dashboard')
@section('subheader', 'Selamat datang, ' . auth()->user()->name)

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Job</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $totalJobs }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Belum Dikerjakan</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $activeJobs - $inProgress }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Sedang Dikerjakan</p>
        <p class="text-2xl font-semibold text-blue-600 mt-1">{{ $inProgress }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Selesai</p>
        <p class="text-2xl font-semibold text-green-600 mt-1">{{ $doneJobs }}</p>
    </div>
</div>

{{-- Recent Jobs --}}
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-medium text-gray-900">Job Terbaru</h3>
        <a href="{{ route('crew.jobs.index') }}"
           class="text-xs text-gray-400 hover:text-gray-700 transition">
            Lihat Semua
        </a>
    </div>

    @if($recentJobs->isEmpty())
    <div class="py-10 text-center text-sm text-gray-400">
        Belum ada job yang diassign ke kamu.
    </div>
    @else
    <div class="divide-y divide-gray-50">
        @foreach($recentJobs as $job)
        @php
        $statusColor = match($job->status->value) {
            'todo'       => 'bg-gray-100 text-gray-600',
            'inprogress' => 'bg-blue-50 text-blue-700',
            'review'     => 'bg-yellow-50 text-yellow-700',
            'done'       => 'bg-green-50 text-green-700',
            default      => 'bg-gray-100 text-gray-600',
        };
        @endphp
        <div class="px-5 py-3 flex items-center justify-between gap-3 hover:bg-gray-50 transition">
            <div class="flex-1 min-w-0">
                <a href="{{ route('crew.jobs.show', $job) }}"
                   class="text-sm font-medium text-gray-900 hover:text-blue-600 transition truncate">
                    {{ $job->title }}
                </a>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $job->project->name }}
                    @if($job->team)
                    · {{ $job->team->team_name }}
                    @endif
                </p>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium flex-shrink-0 {{ $statusColor }}">
                {{ $job->status->label() }}
            </span>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection