@extends('layouts.app')

@section('title', 'Edit Project')
@section('header', 'Edit Project')
@section('subheader', $project->code . ' — ' . $project->name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.projects.update', $project) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Client</label>
                <select name="client_id"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}"
                            {{ old('client_id', $project->client_id) === $client->id ? 'selected' : '' }}>
                        {{ $client->contact_name }}
                        @if($client->company_name) — {{ $client->company_name }} @endif
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Project</label>
                <input type="text" name="name" value="{{ old('name', $project->name) }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
                    <input type="text" name="category" value="{{ old('category', $project->category) }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Prioritas</label>
                    <select name="priority"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-gray-900">
                        @foreach(['low', 'medium', 'high', 'urgent'] as $p)
                        <option value="{{ $p }}" {{ old('priority', $project->priority->value) === $p ? 'selected' : '' }}>
                            {{ ucfirst($p) }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Deadline</label>
                <input type="date" name="deadline"
                       value="{{ old('deadline', $project->deadline?->format('Y-m-d')) }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('description', $project->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Catatan Internal</label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('notes', $project->notes) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg
                               hover:bg-gray-700 transition">
                    Simpan Perubahan
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