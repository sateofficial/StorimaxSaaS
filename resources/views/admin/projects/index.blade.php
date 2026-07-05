@extends('layouts.app')

@section('title', 'Projects')
@section('header', 'Projects')
@section('subheader', 'Kelola semua project agency')

@section('content')

{{-- Header action --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        {{-- Filter status --}}
        @foreach(['', 'draft', 'active', 'review', 'done', 'archived'] as $s)
        <a href="{{ request()->fullUrlWithQuery(['status' => $s]) }}"
           class="text-xs px-3 py-1.5 rounded-full transition
                  {{ request('status', '') === $s
                      ? 'bg-gray-900 text-white'
                      : 'bg-white border border-gray-200 text-gray-500 hover:border-gray-400' }}">
            {{ $s === '' ? 'Semua' : ucfirst($s) }}
        </a>
        @endforeach
    </div>
    <a href="{{ route('admin.projects.create') }}"
       class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
        + Buat Project
    </a>
</div>

{{-- Project list --}}
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">

    @php
    $filtered = request('status')
        ? $projects->filter(fn($p) => $p->status->value === request('status'))
        : $projects;
    @endphp

    @if($filtered->isEmpty())
    <div class="py-16 text-center text-sm text-gray-400">
        Tidak ada project ditemukan.
    </div>
    @else
    <div class="divide-y divide-gray-50">
        @foreach($filtered as $project)
        @php
        $statusColor = match($project->status->value) {
            'draft'    => 'bg-gray-100 text-gray-600',
            'active'   => 'bg-blue-50 text-blue-700',
            'review'   => 'bg-yellow-50 text-yellow-700',
            'done'     => 'bg-green-50 text-green-700',
            'archived' => 'bg-red-50 text-red-600',
            default    => 'bg-gray-100 text-gray-600',
        };
        $priorityColor = match($project->priority->value) {
            'low'    => 'text-gray-400',
            'medium' => 'text-blue-500',
            'high'   => 'text-orange-500',
            'urgent' => 'text-red-500',
            default  => 'text-gray-400',
        };
        @endphp
        <div class="px-5 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition">

            {{-- Info utama --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.projects.show', $project) }}"
                       class="text-sm font-medium text-gray-900 hover:text-blue-600 transition truncate">
                        {{ $project->name }}
                    </a>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium flex-shrink-0 {{ $statusColor }}">
                        {{ $project->status->label() }}
                    </span>
                    <span class="text-xs font-medium flex-shrink-0 {{ $priorityColor }}">
                        {{ $project->priority->label() }}
                    </span>
                </div>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-xs text-gray-400">{{ $project->code }}</span>
                    <span class="text-xs text-gray-300">·</span>
                    <span class="text-xs text-gray-400">{{ $project->client->contact_name }}</span>
                    @if($project->category)
                    <span class="text-xs text-gray-300">·</span>
                    <span class="text-xs text-gray-400">{{ $project->category }}</span>
                    @endif
                    @if($project->deadline)
                    @php
                        $daysRemaining = (int) now()->diffInDays($project->deadline, false);
                        $isOverdue = $daysRemaining < 0 && $project->status->value !== 'done';
                        $isDueToday = $daysRemaining === 0;
                        $deadlineColor = match(true) {
                            $isOverdue            => 'text-red-500',
                            $isDueToday           => 'text-amber-600',
                            $daysRemaining <= 3   => 'text-amber-600',
                            default               => 'text-gray-400',
                        };
                        $deadlineLabel = match(true) {
                            $isOverdue            => 'Telat ' . abs($daysRemaining) . ' hr',
                            $isDueToday           => 'Deadline hari ini',
                            $daysRemaining === 1  => 'Sisa 1 hari',
                            default               => 'Sisa ' . $daysRemaining . ' hr',
                        };
                    @endphp
                    <span class="text-xs text-gray-300">·</span>
                    <span class="text-xs {{ $project->isOverdue() ? 'text-red-500' : 'text-gray-400' }}">
                        {{ $project->deadline->format('d M Y') }}
                    </span>
                    <span class="inline-flex items-center gap-0.5 text-xs font-medium {{ $deadlineColor }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="{{ $isOverdue ? 'M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' }}"/>
                        </svg>
                        {{ $deadlineLabel }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Tim count --}}
            <div class="flex-shrink-0 text-center">
                <p class="text-sm font-medium text-gray-900">{{ $project->teams->count() }}</p>
                <p class="text-xs text-gray-400">tim</p>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('admin.projects.show', $project) }}"
                   class="text-xs text-gray-400 hover:text-gray-700 transition">Detail</a>
                <a href="{{ route('admin.projects.edit', $project) }}"
                   class="text-xs text-gray-400 hover:text-gray-700 transition">Edit</a>
                <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                      onsubmit="return confirm('Hapus project {{ $project->name }}?')">
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