@extends('layouts.app')

@section('title', $job->title)
@section('header', $job->title)
@section('subheader', $job->project ? $job->project->code . ' — ' . $job->project->name : '—')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kolom kiri: Detail + Update status --}}
    <div class="lg:col-span-1 space-y-4">

        {{-- Info job --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5 space-y-4">

            {{-- Status --}}
            <div>
                <p class="text-xs text-gray-400 mb-2">Status</p>
                @if(auth()->user()->isAdmin())
                <span class="text-sm font-medium text-gray-900">{{ $job->status->label() }}</span>
                @else
                <span class="text-sm font-medium text-gray-900">{{ $job->status->label() }}</span>
                @endif
            </div>

            @if($job->gdrive_link)
            <div class="pt-2 border-t border-gray-50">
                <p class="text-xs text-gray-400 mb-1">Link Google Drive</p>
                <a href="{{ $job->gdrive_link }}" target="_blank"
                   class="text-sm text-blue-600 hover:underline inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Lihat File
                </a>
            </div>
            @endif

            {{-- Detail info --}}
            <div class="space-y-3 pt-2 border-t border-gray-50">
                <div>
                    <p class="text-xs text-gray-400">Project</p>
                    <a href="{{ $job->project ? route('admin.projects.show', $job->project) : '#' }}"
                       class="text-sm text-blue-600 hover:underline">
                        {{ $job->project?->name ?? '—' }}
                    </a>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Assigned to</p>
                    <p class="text-sm text-gray-900">{{ $job->assignee?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Prioritas</p>
                    @php
                    $priorityColor = match($job->priority->value) {
                        'low'    => 'text-gray-500',
                        'medium' => 'text-blue-600',
                        'high'   => 'text-orange-500',
                        'urgent' => 'text-red-500',
                        default  => 'text-gray-500',
                    };
                    @endphp
                    <p class="text-sm font-medium {{ $priorityColor }}">
                        {{ $job->priority->label() }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Deadline</p>
                    <p class="text-sm {{ $job->isOverdue() ? 'text-red-500' : 'text-gray-900' }}">
                        {{ $job->deadline ? $job->deadline->format('d M Y') : '—' }}
                    </p>
                </div>
                @if($job->started_at)
                <div>
                    <p class="text-xs text-gray-400">Mulai dikerjakan</p>
                    <p class="text-sm text-gray-900">{{ $job->started_at->format('d M Y H:i') }}</p>
                </div>
                @endif
                @if($job->completed_at)
                <div>
                    <p class="text-xs text-gray-400">Selesai</p>
                    <p class="text-sm text-gray-900">{{ $job->completed_at->format('d M Y H:i') }}</p>
                </div>
                @endif
            </div>

            @if($job->description)
            <div class="pt-2 border-t border-gray-50">
                <p class="text-xs text-gray-400 mb-1">Deskripsi</p>
                <p class="text-sm text-gray-700">{{ $job->description }}</p>
            </div>
            @endif

            @if(auth()->user()->isAdmin())
            {{-- Actions --}}
            <div class="pt-2 border-t border-gray-50 flex gap-3">
                <a href="{{ route('admin.jobs.edit', $job) }}"
                   class="text-xs text-gray-500 hover:text-gray-900 transition">Edit</a>
                <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}"
                      onsubmit="return confirm('Hapus job ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition">
                        Hapus
                    </button>
                </form>
            </div>
            @endif
        </div>

        @if(auth()->user()->isAdmin())
        {{-- Update Status --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Update Status</h3>
            <form method="POST" action="{{ route('admin.jobs.update-status', $job) }}"
                  class="space-y-3">
                @csrf
                @method('PATCH')
                <select name="status"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @foreach(['todo', 'inprogress', 'review', 'done'] as $s)
                    <option value="{{ $s }}" {{ $job->status->value === $s ? 'selected' : '' }}>
                        {{ match($s) {
                            'todo'       => 'To Do',
                            'inprogress' => 'In Progress',
                            'review'     => 'Review',
                            'done'       => 'Done',
                            default      => ucfirst($s)
                        } }}
                    </option>
                    @endforeach
                </select>
                <textarea name="note" rows="3"
                          placeholder="Catatan (opsional)..."
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none"></textarea>
                <button type="submit"
                        class="w-full py-2 bg-gray-900 text-white text-sm font-medium rounded-lg
                               hover:bg-gray-700 transition">
                    Update Status
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Kolom kanan: Activity log --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-medium text-gray-900">
                    Activity Log
                    <span class="ml-2 text-xs text-gray-400 font-normal">
                        {{ $job->logs->count() }} aktivitas
                    </span>
                </h3>
            </div>

            @if($job->logs->isEmpty())
            <div class="py-10 text-center text-sm text-gray-400">
                Belum ada aktivitas.
            </div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($job->logs->sortByDesc('created_at') as $log)
                @php
                $newStatusColor = match($log->new_status) {
                    'todo'       => 'bg-gray-100 text-gray-600',
                    'inprogress' => 'bg-blue-50 text-blue-700',
                    'review'     => 'bg-yellow-50 text-yellow-700',
                    'done'       => 'bg-green-50 text-green-700',
                    default      => 'bg-gray-100 text-gray-600',
                };
                @endphp
                <div class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                            <span class="text-xs text-white font-medium">
                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-medium text-gray-900">{{ $log->user->name }}</span>
                                <span class="text-xs text-gray-400">mengubah status</span>
                                @if($log->old_status)
                                <span class="text-xs px-1.5 py-0.5 rounded bg-gray-100 text-gray-500">
                                    {{ $log->old_status }}
                                </span>
                                <span class="text-xs text-gray-400">→</span>
                                @endif
                                <span class="text-xs px-1.5 py-0.5 rounded font-medium {{ $newStatusColor }}">
                                    {{ $log->new_status }}
                                </span>
                            </div>
                            @if($log->note)
                            <p class="text-xs text-gray-500 mt-1">{{ $log->note }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

</div>
@endsection