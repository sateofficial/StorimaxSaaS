@extends('layouts.app')

@section('title', 'Portofolio')
@section('header', 'Portofolio')
@section('subheader', 'Karya dan project dari Storimax')

@section('content')

@if($portfolios->isEmpty())
<div class="py-16 text-center text-sm text-gray-400">
    Belum ada portofolio yang dipublikasikan.
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($portfolios as $portfolio)
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition">
        {{-- Thumbnail --}}
        <div class="aspect-video bg-gray-100 flex items-center justify-center">
            @if($portfolio->thumbnail_path)
            <img src="{{ asset('storage/' . $portfolio->thumbnail_path) }}"
                 alt="{{ $portfolio->title }}"
                 class="w-full h-full object-cover">
            @else
            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2z"/>
            </svg>
            @endif
        </div>

        {{-- Info --}}
        <div class="p-4">
            <a href="{{ route('client.portfolios.show', $portfolio) }}"
               class="text-sm font-medium text-gray-900 hover:text-blue-600 transition">
                {{ $portfolio->title }}
            </a>
            @if($portfolio->description)
            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $portfolio->description }}</p>
            @endif
            <div class="flex items-center gap-2 mt-3">
                @foreach($portfolio->tags as $tag)
                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                    {{ $tag->tag }}
                </span>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
