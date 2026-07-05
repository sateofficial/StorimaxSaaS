@extends('layouts.app')

@section('title', $portfolio->title)
@section('header', $portfolio->title)
@section('subheader', $portfolio->project->name . ' · ' . $portfolio->project->code)

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Thumbnail --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden mb-6">
        <div class="aspect-video bg-gray-100 flex items-center justify-center">
            @if($portfolio->thumbnail_path)
            <img src="{{ asset('storage/' . $portfolio->thumbnail_path) }}"
                 alt="{{ $portfolio->title }}"
                 class="w-full h-full object-cover">
            @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2z"/>
                </svg>
                <p class="text-sm text-gray-400">Belum ada thumbnail</p>
            </div>
            @endif
        </div>
    </div>

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <span class="text-xs px-3 py-1.5 rounded-full font-medium
                         {{ $portfolio->is_public ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                {{ $portfolio->is_public ? 'Publik' : 'Privat' }}
            </span>
            @if($portfolio->published_at)
            <span class="text-xs text-gray-400">
                Dipublikasikan {{ $portfolio->published_at->diffForHumans() }}
            </span>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.portfolios.edit', $portfolio) }}"
               class="px-4 py-2 border border-gray-200 text-sm rounded-lg hover:bg-gray-50 transition">
                Edit
            </a>
            <form method="POST" action="{{ route('admin.portfolios.toggle-public', $portfolio) }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                    {{ $portfolio->is_public ? 'Tutup Publik' : 'Publikasikan' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Detail --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
        <div>
            <p class="text-xs text-gray-400 mb-1">Project</p>
            <a href="{{ route('admin.projects.show', $portfolio->project) }}"
               class="text-sm text-blue-600 hover:underline">
                {{ $portfolio->project->name }}
            </a>
        </div>

        <div>
            <p class="text-xs text-gray-400 mb-1">Client</p>
            <p class="text-sm text-gray-900">{{ $portfolio->project->client->contact_name }}</p>
        </div>

        <div>
            <p class="text-xs text-gray-400 mb-1">Dibuat oleh</p>
            <p class="text-sm text-gray-900">{{ $portfolio->creator->name }}</p>
        </div>

        @if($portfolio->category)
        <div>
            <p class="text-xs text-gray-400 mb-1">Kategori</p>
            <p class="text-sm text-gray-900">{{ $portfolio->category }}</p>
        </div>
        @endif

        @if($portfolio->description)
        <div>
            <p class="text-xs text-gray-400 mb-1">Deskripsi</p>
            <p class="text-sm text-gray-700">{{ $portfolio->description }}</p>
        </div>
        @endif

        @if($portfolio->tags->isNotEmpty())
        <div>
            <p class="text-xs text-gray-400 mb-2">Tags</p>
            <div class="flex items-center gap-2 flex-wrap">
                @foreach($portfolio->tags as $tag)
                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                    {{ $tag->tag }}
                </span>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.portfolios.index') }}"
           class="text-sm text-gray-400 hover:text-gray-700 transition">
            ← Kembali ke Portofolio
        </a>
    </div>
</div>

@endsection
