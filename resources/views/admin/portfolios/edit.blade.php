@extends('layouts.app')

@section('title', 'Edit Portofolio')
@section('header', 'Edit Portofolio')
@section('subheader', $portfolio->title)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.portfolios.update', $portfolio) }}"
              enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Project <span class="text-red-500">*</span></label>
                <select name="project_id" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-gray-900">
                    <option value="">Pilih project...</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ $portfolio->project_id == $project->id ? 'selected' : '' }}>
                        {{ $project->code }} — {{ $project->name }} ({{ $project->client->contact_name }})
                    </option>
                    @endforeach
                </select>
                @error('project_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $portfolio->title) }}" required
                       placeholder="Judul portofolio"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
                <textarea name="description" rows="4"
                          placeholder="Deskripsi portofolio..."
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('description', $portfolio->description) }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Thumbnail</label>
                @if($portfolio->thumbnail_path)
                <div class="mb-2 flex items-center gap-3">
                    <img src="{{ asset('storage/' . $portfolio->thumbnail_path) }}"
                         alt="" class="w-16 h-16 rounded-lg object-cover">
                    <span class="text-xs text-gray-400">Thumbnail saat ini</span>
                </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0 file:text-sm file:font-medium
                              file:bg-gray-900 file:text-white hover:file:bg-gray-700
                              file:cursor-pointer file:transition">
                <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah thumbnail. Maks. 2MB.</p>
                @error('thumbnail') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
                <input type="text" name="category" value="{{ old('category', $portfolio->category) }}"
                       placeholder="misal: Branding, Videografi, Fotografi"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
                @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tags</label>
                <input type="text" name="tags" value="{{ old('tags', $tags) }}"
                       placeholder="Pisahkan dengan koma, misal: video, company profile, branding"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
                <p class="text-xs text-gray-400 mt-1">Pisahkan tag dengan koma (,)</p>
                @error('tags') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_public" value="1"
                           class="sr-only peer" {{ old('is_public', $portfolio->is_public) ? 'checked' : '' }}>
                    <div class="w-9 h-5 bg-gray-200 rounded-full peer
                                peer-checked:bg-gray-900 peer-checked:after:translate-x-full
                                after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                after:bg-white after:rounded-full after:h-4 after:w-4 after:transition">
                    </div>
                </label>
                <span class="text-sm text-gray-700">
                    {{ $portfolio->is_public ? 'Dipublikasikan' : 'Tidak dipublikasikan' }}
                </span>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg
                               hover:bg-gray-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.portfolios.index') }}"
                   class="px-6 py-2 text-sm text-gray-500 hover:text-gray-700 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
