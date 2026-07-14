@extends('layouts.app')

@section('title', 'Profil')
@section('header', 'Profil')
@section('subheader', 'Kelola informasi akun kamu')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Info Akun --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <div class="flex items-center gap-5">
            {{-- Avatar --}}
            <div class="relative group">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden">
                    @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}"
                         alt="{{ $user->name }}"
                         class="w-full h-full object-cover">
                    @else
                    <span class="text-2xl font-semibold text-gray-600">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                    @endif
                </div>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->role->label() }}</p>
                <p class="text-sm text-gray-400">{{ $user->email }}</p>
            </div>
        </div>
    </div>

    {{-- Upload Foto --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h3 class="text-sm font-medium text-gray-900 mb-4">Foto Profil</h3>
        <form method="POST" action="{{ route('profile.avatar') }}"
              enctype="multipart/form-data" class="space-y-3"
              x-data="{ fileName: '', fileSize: 0, maxSize: 2 * 1024 * 1024, errorMsg: '' }">
            @csrf
            <div class="relative">
                <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" required
                       @@change="const f = $event.target.files[0]; if(f) { fileName = f.name; fileSize = f.size; errorMsg = f.size > maxSize ? 'Ukuran file melebihi batas maksimal 2MB' : ''; } else { fileName = ''; fileSize = 0; errorMsg = ''; }"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0 file:text-sm file:font-medium
                              file:bg-gray-900 file:text-white hover:file:bg-gray-700
                              file:cursor-pointer file:transition">
            </div>
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-400">Maks. 2MB. Format: JPG, PNG, WebP</p>
                <span x-show="fileName" class="text-xs text-gray-500 truncate max-w-[200px]" x-text="fileName"></span>
            </div>
            <template x-if="errorMsg">
                <p class="text-xs text-red-500 flex items-center gap-1" x-text="errorMsg"></p>
            </template>
            @error('avatar') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            <button type="submit"
                    x-bind:disabled="errorMsg || !fileSize"
                    x-bind:class="errorMsg || !fileSize ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-700'"
                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg transition">
                <span x-show="!fileSize">Pilih file terlebih dahulu</span>
                <span x-show="fileSize && !errorMsg">Upload Foto</span>
                <span x-show="errorMsg">File terlalu besar</span>
            </button>
        </form>
    </div>

    {{-- Edit Profil --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h3 class="text-sm font-medium text-gray-900 mb-4">Informasi Profil</h3>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       placeholder="08xxxxxxxxxx"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
                @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg
                           hover:bg-gray-700 transition">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Ganti Password --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h3 class="text-sm font-medium text-gray-900 mb-4">Ganti Password</h3>
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" required
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
                @error('current_password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Password Baru</label>
                    <input type="password" name="password" required min="8"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
            </div>

            <button type="submit"
                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg
                           hover:bg-gray-700 transition">
                Ganti Password
            </button>
        </form>
    </div>

</div>
@endsection
