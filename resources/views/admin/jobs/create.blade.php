@extends('layouts.app')

@section('title', 'Tambah Job')
@section('header', 'Tambah Job')
@section('subheader', $project->code . ' — ' . $project->name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.projects.jobs.store', $project) }}"
              class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    Judul Job <span class="text-red-400">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}"
                       placeholder="misal: Editing video highlight"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tim</label>
                    <select name="project_team_id"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-gray-900">
                        <option value="">Tanpa tim</option>
                        @foreach($teams as $team)
                        <option value="{{ $team->id }}"
                                {{ old('project_team_id') === $team->id ? 'selected' : '' }}>
                            {{ $team->team_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Assign ke Crew</label>
                    <select name="assigned_to"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-gray-900">
                        <option value="">Belum diassign</option>
                        @foreach($crews as $crew)
                        <option value="{{ $crew->id }}"
                                {{ old('assigned_to') === $crew->id ? 'selected' : '' }}>
                            {{ $crew->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Prioritas <span class="text-red-400">*</span>
                    </label>
                    <select name="priority"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-gray-900">
                        @foreach(['low', 'medium', 'high', 'urgent'] as $p)
                        <option value="{{ $p }}"
                                {{ old('priority', 'medium') === $p ? 'selected' : '' }}>
                            {{ ucfirst($p) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                          placeholder="Detail pekerjaan, brief singkat..."
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                <textarea name="notes" rows="2"
                          placeholder="Catatan tambahan..."
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg
                               hover:bg-gray-700 transition">
                    Tambah Job
                </button>
                <a href="{{ route('admin.projects.show', $project) }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection