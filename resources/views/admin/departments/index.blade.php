@extends('layouts.app')

@section('title', 'Departments')
@section('header', 'Departments')
@section('subheader', 'Kelola divisi dan departemen agency')

@section('content')
<div class="flex gap-6">

    {{-- Form tambah --}}
    <div class="w-72 flex-shrink-0">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Tambah Department</h3>
            <form method="POST" action="{{ route('admin.departments.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Department</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="misal: Desain Grafis"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                              placeholder="Deskripsi singkat department..."
                              class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                     focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('description') }}</textarea>
                </div>
                <button type="submit"
                        class="w-full py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                    Tambah
                </button>
            </form>
        </div>
    </div>

    {{-- List departments --}}
    <div class="flex-1 min-w-0">
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-medium text-gray-900">
                    Semua Department
                    <span class="ml-2 text-xs text-gray-400 font-normal">{{ $departments->count() }} department</span>
                </h3>
            </div>

            @if($departments->isEmpty())
            <div class="px-5 py-10 text-center text-sm text-gray-400">
                Belum ada department.
            </div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($departments as $dept)
                <div class="px-5 py-4 flex items-center justify-between"
                     x-data="{ editing: false }">

                    {{-- View mode --}}
                    <div x-show="!editing" class="flex-1 min-w-0">
                        <div class="flex items-center gap-3">
                            <p class="text-sm font-medium text-gray-900">{{ $dept->name }}</p>
                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full flex-shrink-0">
                                {{ $dept->users_count }} anggota
                            </span>
                        </div>
                        @if($dept->description)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $dept->description }}</p>
                        @endif
                    </div>

                    {{-- Edit mode --}}
                    <form x-show="editing" method="POST"
                          action="{{ route('admin.departments.update', $dept) }}"
                          class="flex-1 flex gap-2 min-w-0">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" value="{{ $dept->name }}"
                               class="flex-1 min-w-0 px-3 py-1.5 text-sm border border-gray-200 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-gray-900">
                        <button type="submit"
                                class="px-3 py-1.5 bg-gray-900 text-white text-xs rounded-lg hover:bg-gray-700 transition flex-shrink-0">
                            Simpan
                        </button>
                    </form>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 ml-4 flex-shrink-0">
                        <button @click="editing = !editing"
                                class="text-xs text-gray-400 hover:text-gray-700 transition">
                            <span x-text="editing ? 'Batal' : 'Edit'"></span>
                        </button>
                        <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}"
                              onsubmit="return confirm('Hapus department {{ $dept->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-xs text-red-400 hover:text-red-600 transition">
                                Hapus
                            </button>
                        </form>
                    </div>

                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

</div>
@endsection