@extends('layouts.app')

@section('title', 'Portofolio')
@section('header', 'Portofolio')
@section('subheader', 'Kelola portofolio dan karya agency')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        @foreach(['', 'public', 'private'] as $s)
        <a href="{{ request()->fullUrlWithQuery(['status' => $s]) }}"
           class="text-xs px-3 py-1.5 rounded-full transition
                  {{ request('status', '') === $s
                      ? 'bg-gray-900 text-white'
                      : 'bg-white border border-gray-200 text-gray-500 hover:border-gray-400' }}">
            {{ $s === '' ? 'Semua' : ucfirst($s) }}
        </a>
        @endforeach
    </div>
    <a href="{{ route('admin.portfolios.create') }}"
       class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
        + Tambah Portofolio
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">

    @php
    $filtered = match(request('status')) {
        'public'  => $portfolios->filter(fn($p) => $p->is_public),
        'private' => $portfolios->filter(fn($p) => !$p->is_public),
        default   => $portfolios,
    };
    @endphp

    @if($filtered->isEmpty())
    <div class="py-16 text-center text-sm text-gray-400">
        Tidak ada portofolio ditemukan.
    </div>
    @else
    <div class="divide-y divide-gray-50">
        @foreach($filtered as $portfolio)
        <div class="px-5 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if($portfolio->thumbnail_path)
                        <img src="{{ asset('storage/' . $portfolio->thumbnail_path) }}"
                             alt="" class="w-full h-full object-cover">
                        @else
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2z"/>
                        </svg>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('admin.portfolios.show', $portfolio) }}"
                           class="text-sm font-medium text-gray-900 hover:text-blue-600 transition">
                            {{ $portfolio->title }}
                        </a>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-gray-400">{{ $portfolio->project->name }}</span>
                            <span class="text-xs text-gray-300">·</span>
                            <span class="text-xs {{ $portfolio->is_public ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $portfolio->is_public ? 'Publik' : 'Privat' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <form method="POST" action="{{ route('admin.portfolios.toggle-public', $portfolio) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="text-xs {{ $portfolio->is_public ? 'text-yellow-500' : 'text-green-500' }} hover:underline">
                        {{ $portfolio->is_public ? 'Tutup' : 'Publikasikan' }}
                    </button>
                </form>
                <a href="{{ route('admin.portfolios.edit', $portfolio) }}"
                   class="text-xs text-gray-400 hover:text-gray-700 transition">Edit</a>
                <form method="POST" action="{{ route('admin.portfolios.destroy', $portfolio) }}"
                      onsubmit="return confirm('Hapus portofolio {{ $portfolio->title }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
