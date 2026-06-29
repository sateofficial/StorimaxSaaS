@extends('layouts.app')

@section('title', 'Buat Project')
@section('header', 'Buat Project Baru')
@section('subheader', 'Isi detail project dan assign tim setelah dibuat')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.projects.store') }}" class="space-y-4">
            @csrf

            {{-- Client --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Client <span class="text-red-400">*</span></label>
                <select name="client_id"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-gray-900">
                    <option value="">Pilih client...</option>
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id') === $client->id ? 'selected' : '' }}>
                        {{ $client->contact_name }}
                        @if($client->company_name) — {{ $client->company_name }} @endif
                    </option>
                    @endforeach
                </select>
                @error('client_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nama project --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Project <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                       placeholder="misal: Wedding Foto Rizky & Dewi"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori & Priority --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
                    <input type="text" name="category" value="{{ old('category') }}"
                           placeholder="foto, video, branding..."
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Prioritas <span class="text-red-400">*</span></label>
                    <select name="priority"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-gray-900">
                        <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high"   {{ old('priority') === 'high'   ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            {{-- Deadline --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Deadline</label>
                <input type="date" name="deadline" value="{{ old('deadline') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                          placeholder="Detail project, brief singkat..."
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('description') }}</textarea>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Catatan Internal</label>
                <textarea name="notes" rows="2"
                          placeholder="Catatan khusus untuk tim internal..."
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('notes') }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg
                               hover:bg-gray-700 transition">
                    Buat Project
                </button>
                <a href="{{ route('admin.projects.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection