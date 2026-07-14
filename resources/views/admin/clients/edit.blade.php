@extends('layouts.app')

@section('title', 'Edit Client')
@section('header', 'Edit Client')
@section('subheader', 'Update informasi client')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.clients.update', $client) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Client</label>
                <input type="text" name="contact_name" value="{{ old('contact_name', $client->contact_name) }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
                @error('contact_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Perusahaan</label>
                <input type="text" name="company_name" value="{{ old('company_name', $client->company_name) }}"
                       placeholder="Opsional"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>

            {{-- Info Akun Login (hanya admin yang lihat) --}}
            <div class="bg-gray-50 rounded-lg p-4 space-y-4 border border-gray-100">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Informasi Akun Login</h4>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Email (untuk login)</label>
                    <input type="email" name="email" value="{{ old('email', $client->user->email) }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Password Baru
                        <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password" placeholder="Min. 8 karakter"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $client->phone) }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Akun Instagram</label>
                <input type="text" name="instagram" value="{{ old('instagram', $client->instagram) }}"
                       placeholder="@username"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Alamat / Instansi</label>
                <textarea name="address" rows="3"
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('address', $client->address) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Catatan Internal</label>
                <textarea name="notes" rows="3" placeholder="Catatan internal tentang client ini..."
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('notes', $client->notes) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg
                               hover:bg-gray-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.clients.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection