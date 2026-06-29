@extends('layouts.app')

@section('title', 'Edit Job')
@section('header', 'Edit Job')
@section('subheader', $job->project->code . ' — ' . $job->project->name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.jobs.update', $job) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Judul Job</label>
                <input type="text" name="title" value="{{ old('title', $job->title) }}"
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
                                {{ old('project_team_id', $job->project_team_id) === $team->id ? 'selected' : '' }}>
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
                                {{ old('assigned_to', $job->assigned_to) === $crew->id ? 'selected' : '' }}>
                            {{ $crew->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Prioritas</label>
                    <select name="priority"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-gray-900">
                        @foreach(['low', 'medium', 'high', 'urgent'] as $p)
                        <option value="{{ $p }}"
                                {{ old('priority', $job->priority->value) === $p ? 'selected' : '' }}>
                            {{ ucfirst($p) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Deadline</label>
                    <input type="date" name="deadline"
                           value="{{ old('deadline', $job->deadline?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('description', $job->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('notes', $job->notes) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg
                               hover:bg-gray-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.jobs.show', $job) }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection