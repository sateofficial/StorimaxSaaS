@extends('layouts.app')

@section('title', 'Clients')
@section('header', 'Clients')
@section('subheader', 'Kelola data client agency')

@section('content')
<div class="flex gap-6">

    {{-- Form tambah client --}}
    <div class="w-72 flex-shrink-0">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Tambah Client</h3>
            <form method="POST" action="{{ route('admin.clients.store') }}" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Client</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}"
                           placeholder="Nama lengkap client"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @error('contact_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Email (untuk login)</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="email@client.com"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Password</label>
                    <input type="password" name="password" placeholder="Min. 8 karakter"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Perusahaan</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}"
                           placeholder="PT / CV / Instansi (opsional)"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Akun Instagram</label>
                    <input type="text" name="instagram" value="{{ old('instagram') }}"
                           placeholder="@username"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Alamat / Instansi</label>
                    <textarea name="address" rows="2" placeholder="Alamat lengkap..."
                              class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                     focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none">{{ old('address') }}</textarea>
                </div>

                <button type="submit"
                        class="w-full py-2 bg-gray-900 text-white text-sm font-medium rounded-lg
                               hover:bg-gray-700 transition">
                    Tambah Client
                </button>
            </form>
        </div>
    </div>

    {{-- List clients --}}
    <div class="flex-1 min-w-0">
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-medium text-gray-900">
                    Semua Client
                    <span class="ml-2 text-xs text-gray-400 font-normal">{{ $clients->count() }} client</span>
                </h3>
            </div>

            @if($clients->isEmpty())
            <div class="px-5 py-10 text-center text-sm text-gray-400">
                Belum ada client.
            </div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($clients as $client)
                <div class="px-5 py-4 flex items-center justify-between gap-4">

                    {{-- Avatar + info --}}
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-medium text-orange-700">
                                {{ strtoupper(substr($client->contact_name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $client->contact_name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $client->company_name ?? 'Client' }}</p>
                        </div>
                    </div>

                    {{-- Detail --}}
                    <div class="flex items-center gap-3 flex-shrink-0">
                        @if($client->company_name)
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                            {{ $client->company_name }}
                        </span>
                        @endif
                        @if($client->instagram)
                        <span class="text-xs text-gray-400">{{ $client->instagram }}</span>
                        @endif
                        @if($client->phone)
                        <span class="text-xs text-gray-400">{{ $client->phone }}</span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <a href="{{ route('admin.clients.edit', $client) }}"
                           class="text-xs text-gray-400 hover:text-gray-700 transition">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.clients.destroy', $client) }}"
                              onsubmit="return confirm('Hapus client {{ $client->contact_name }}?')">
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