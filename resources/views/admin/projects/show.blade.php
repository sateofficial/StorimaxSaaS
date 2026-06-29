@extends('layouts.app')

@section('title', $project->code)
@section('header', $project->name)
@section('subheader', $project->code . ' · ' . $project->client->contact_name)

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
        </div>
    </div>

    @if($project->description)
    <p class="text-sm text-gray-500 mt-4 pt-4 border-t border-gray-100">{{ $project->description }}</p>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kolom kiri: Tim --}}
    <div class="lg:col-span-1 space-y-4">

        {{-- Tambah tim --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Tambah Tim</h3>
            <form method="POST" action="{{ route('admin.projects.teams.store', $project) }}"
                  class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Tim</label>
                    <input type="text" name="team_name"
                           placeholder="misal: Tim Foto"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">PIC</label>
                    <select name="pic_user_id"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-gray-900">
                        <option value="">Tanpa PIC</option>
                        @foreach($crews as $crew)
                        <option value="{{ $crew->id }}">{{ $crew->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                        class="w-full py-2 bg-gray-900 text-white text-sm font-medium rounded-lg
                               hover:bg-gray-700 transition">
                    Tambah Tim
                </button>
            </form>
        </div>

        {{-- List tim --}}
        @forelse($project->teams as $team)
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-medium text-gray-900">{{ $team->team_name }}</h4>
                <form method="POST"
                      action="{{ route('admin.projects.teams.destroy', [$project, $team]) }}"
                      onsubmit="return confirm('Hapus tim ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition">
                        Hapus
                    </button>
                </form>
            </div>

            {{-- PIC --}}
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xs text-gray-400">PIC:</span>
                <span class="text-xs font-medium text-gray-700">
                    {{ $team->pic?->name ?? 'Belum ditentukan' }}
                </span>
            </div>

            {{-- Members --}}
            <div class="space-y-1 mb-3">
                @forelse($team->members as $member)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-xs text-gray-600">
                                {{ strtoupper(substr($member->user->name, 0, 1)) }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-700">{{ $member->user->name }}</span>
                    </div>
                    <form method="POST"
                          action="{{ route('admin.projects.teams.members.destroy', [$project, $team, $member]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-gray-300 hover:text-red-400 transition">✕</button>
                    </form>
                </div>
                @empty
                <p class="text-xs text-gray-400">Belum ada anggota.</p>
                @endforelse
            </div>

            {{-- Tambah member --}}
            <form method="POST"
                  action="{{ route('admin.projects.teams.members.store', [$project, $team]) }}"
                  class="flex gap-2">
                @csrf
                <select name="user_id"
                        class="flex-1 px-2 py-1.5 text-xs border border-gray-200 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-gray-900">
                    <option value="">Tambah anggota...</option>
                    @foreach($crews as $crew)
                    <option value="{{ $crew->id }}">{{ $crew->name }}</option>
                    @endforeach
                </select>
                <button type="submit"
                        class="px-3 py-1.5 bg-gray-900 text-white text-xs rounded-lg hover:bg-gray-700 transition">
                    +
                </button>
            </form>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-100 p-5 text-center text-sm text-gray-400">
            Belum ada tim. Tambahkan tim di atas.
        </div>
        @endforelse

    </div>

    {{-- Kolom kanan: Jobs --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-900">
                    Jobs
                    <span class="ml-2 text-xs text-gray-400 font-normal">{{ $project->jobs->count() }} job</span>
                </h3>
                <a href="{{ route('admin.projects.jobs.create', $project) }}"
                   class="text-xs px-3 py-1.5 bg-gray-900 text-white rounded-lg hover:bg-gray-700 transition">
                    + Tambah Job
                </a>
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