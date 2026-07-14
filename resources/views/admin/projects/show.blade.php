@extends('layouts.app')

@section('title', $project->code)
@section('header', $project->name)
@section('subheader', $project->code . ' · ' . optional($project->client)->contact_name ?? '—')

@section('content')

{{-- Top info bar --}}
<div class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
    <div class="flex items-start justify-between gap-6">

        {{-- Detail project --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 flex-1">
            <div>
                <p class="text-xs text-gray-400 mb-1">Status</p>
                @php
                $statusColor = match($project->status->value) {
                    'draft'    => 'bg-gray-100 text-gray-600',
                    'active'   => 'bg-blue-50 text-blue-700',
                    'review'   => 'bg-yellow-50 text-yellow-700',
                    'done'     => 'bg-green-50 text-green-700',
                    'archived' => 'bg-red-50 text-red-600',
                    default    => 'bg-gray-100 text-gray-600',
                };
                @endphp
                <span class="text-xs px-2 py-1 rounded-full font-medium {{ $statusColor }}">
                    {{ $project->status->label() }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Prioritas</p>
                <p class="text-sm font-medium text-gray-900">{{ $project->priority->label() }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Deadline</p>
                <p class="text-sm font-medium {{ $project->isOverdue() ? 'text-red-500' : 'text-gray-900' }}">
                    {{ $project->deadline ? $project->deadline->format('d M Y') : '—' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Kategori</p>
                <p class="text-sm font-medium text-gray-900">{{ $project->category ?? '—' }}</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2 flex-shrink-0">
            @if(auth()->user()->isAdmin())
            {{-- Update status --}}
            <form method="POST" action="{{ route('admin.projects.update-status', $project) }}"
                  class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()"
                        class="text-xs px-2 py-1.5 border border-gray-200 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @foreach(['draft', 'active', 'review', 'done', 'archived'] as $s)
                    <option value="{{ $s }}" {{ $project->status->value === $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.projects.edit', $project) }}"
               class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                Edit
            </a>
            @endif
        </div>
    </div>

    @if($project->description)
    <p class="text-sm text-gray-500 mt-4 pt-4 border-t border-gray-100">{{ $project->description }}</p>
    @endif
</div>

<div class="grid grid-cols-1 gap-6">

    {{-- Jobs section (full width) --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-900">
                    Jobs
                    <span class="ml-2 text-xs text-gray-400 font-normal">{{ $project->jobs->count() }} job</span>
                </h3>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.projects.jobs.create', $project) }}"
                   class="text-xs px-3 py-1.5 bg-gray-900 text-white rounded-lg hover:bg-gray-700 transition">
                    + Tambah Job
                </a>
                @endif
            </div>

            @if($project->jobs->isEmpty())
            <div class="py-10 text-center text-sm text-gray-400">
                Belum ada job. Klik "+ Tambah Job" untuk mulai.
            </div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($project->jobs as $job)
                @php
                $jobStatusColor = match($job->status->value) {
                    'todo'       => 'bg-gray-100 text-gray-600',
                    'inprogress' => 'bg-blue-50 text-blue-700',
                    'review'     => 'bg-yellow-50 text-yellow-700',
                    'done'       => 'bg-green-50 text-green-700',
                    default      => 'bg-gray-100 text-gray-600',
                };
                @endphp
                <div class="px-5 py-3 flex items-center justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900 truncate">{{ $job->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $job->assignee?->name ?? 'Belum diassign' }}
                            @if($job->deadline)
                            · {{ $job->deadline->format('d M Y') }}
                            @endif
                        </p>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium flex-shrink-0 {{ $jobStatusColor }}">
                        {{ $job->status->label() }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

</div>

@endsection