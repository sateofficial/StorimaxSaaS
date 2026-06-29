@extends('layouts.app')

@section('title', 'Users')
@section('header', 'Users')
@section('subheader', 'Kelola akun dan role pengguna sistem')

@section('content')
<div class="flex gap-6">

    {{-- Form tambah user --}}
    <div class="w-72 flex-shrink-0">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Tambah User</h3>
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Nama lengkap"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="email@storimax.id"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Password</label>
                    <input type="password" name="password"
                           placeholder="Min. 8 karakter"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Role</label>
                    <select name="role"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-gray-900">
                        <option value="">Pilih role...</option>
                        <option value="admin"  {{ old('role') === 'admin'  ? 'selected' : '' }}>Admin</option>
                        <option value="atasan" {{ old('role') === 'atasan' ? 'selected' : '' }}>Atasan</option>
                        <option value="crew"   {{ old('role') === 'crew'   ? 'selected' : '' }}>Crew</option>
                    </select>
                    @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Department</label>
                    <select name="department_id"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-gray-900">
                        <option value="">Tanpa department</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') === $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>

                <button type="submit"
                        class="w-full py-2 bg-gray-900 text-white text-sm font-medium rounded-lg
                               hover:bg-gray-700 transition">
                    Tambah User
                </button>
            </form>
        </div>
    </div>

    {{-- List users --}}
    <div class="flex-1 min-w-0">
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-900">
                    Semua User
                    <span class="ml-2 text-xs text-gray-400 font-normal">{{ $users->count() }} user</span>
                </h3>
            </div>

            @if($users->isEmpty())
            <div class="px-5 py-10 text-center text-sm text-gray-400">
                Belum ada user.
            </div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($users as $user)
                <div class="px-5 py-4 flex items-center justify-between gap-4">

                    {{-- Avatar + info --}}
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                                    {{ $user->is_active ? 'bg-gray-900' : 'bg-gray-300' }}">
                            <span class="text-xs font-medium text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                @if(!$user->is_active)
                                <span class="text-xs text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded flex-shrink-0">
                                    Nonaktif
                                </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                        </div>
                    </div>

                    {{-- Role & dept badge --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @php
                        $roleColor = match($user->role->value) {
                            'admin'  => 'bg-blue-50 text-blue-700',
                            'atasan' => 'bg-purple-50 text-purple-700',
                            'crew'   => 'bg-green-50 text-green-700',
                            default  => 'bg-gray-100 text-gray-600',
                        };
                        @endphp
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $roleColor }}">
                            {{ $user->role->label() }}
                        </span>
                        @if($user->department)
                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                            {{ $user->department->name }}
                        </span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="text-xs text-gray-400 hover:text-gray-700 transition">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="text-xs {{ $user->is_active ? 'text-yellow-500 hover:text-yellow-700' : 'text-green-500 hover:text-green-700' }} transition">
                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-xs text-red-400 hover:text-red-600 transition">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

</div>
@endsection