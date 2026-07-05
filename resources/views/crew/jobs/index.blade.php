@extends('layouts.app')

@section('title', 'My Jobs')
@section('header', 'My Jobs')
@section('subheader', 'Job yang diassign ke kamu')

@section('content')

{{-- Filter --}}
<div class="flex items-center gap-3 mb-6">
    @foreach(['', 'todo', 'inprogress', 'review', 'done'] as $s)
    <a href="{{ request()->fullUrlWithQuery(['status' => $s]) }}"
       class="text-xs px-3 py-1.5 rounded-full transition
              {{ request('status', '') === $s
                  ? 'bg-gray-900 text-white'
                  : 'bg-white border border-gray-200 text-gray-500 hover:border-gray-400' }}">
        {{ $s === '' ? 'Semua' : match($s) {
            'todo'       => 'To Do',
            'inprogress' => 'In Progress',
            'review'     => 'Review',
            'done'       => 'Done',
            default      => ucfirst($s)
        } }}
    </a>
    @endforeach
</div>

{{-- List --}}
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">

    @php
    $filtered = request('status')
        ? $jobs->filter(fn($j) => $j->status->value === request('status'))
        : $jobs;
    @endphp

    @if($filtered->isEmpty())
    <div class="py-16 text-center text-sm text-gray-400">
        Tidak ada job ditemukan.
    </div>
    @else
    <div class="divide-y divide-gray-50">
        @foreach($filtered as $job)
        @php
        $statusColor = match($job->status->value) {
            'todo'       => 'bg-gray-100 text-gray-600',
            'inprogress' => 'bg-blue-50 text-blue-700',
            'review'     => 'bg-yellow-50 text-yellow-700',
            'done'       => 'bg-green-50 text-green-700',
            default      => 'bg-gray-100 text-gray-600',
        };
        $priorityColor = match($job->priority->value) {
            'low'    => 'text-gray-400',
            'medium' => 'text-blue-500',
            'high'   => 'text-orange-500',
            'urgent' => 'text-red-500',
            default  => 'text-gray-400',
        };
        @endphp
        <div class="px-5 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition">

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3">
                    <a href="{{ route('crew.jobs.show', $job) }}"
                       class="text-sm font-medium text-gray-900 hover:text-blue-600 transition truncate">
                        {{ $job->title }}
                    </a>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium flex-shrink-0 {{ $statusColor }}">
                        {{ $job->status->label() }}
                    </span>
                    <span class="text-xs font-medium flex-shrink-0 {{ $priorityColor }}">
                        {{ $job->priority->label() }}
                    </span>
                </div>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-xs text-gray-400">
                        {{ $job->project->name }}
                    </span>
                    @if($job->team)
                    <span class="text-xs text-gray-300">·</span>
                    <span class="text-xs text-gray-400">{{ $job->team->team_name }}</span>
                    @endif
                    @if($job->deadline)
                    <span class="text-xs text-gray-300">·</span>
                    <span class="text-xs {{ $job->isOverdue() ? 'text-red-500' : 'text-gray-400' }}">
                        {{ $job->deadline->format('d M Y') }}
                    </span>
                    @endif
                </div>
            </div>

            <a href="{{ route('crew.jobs.show', $job) }}"
               class="text-xs text-gray-400 hover:text-gray-700 transition flex-shrink-0">
                Detail
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
