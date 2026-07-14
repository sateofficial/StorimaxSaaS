@extends('layouts.app')

@section('title', 'Template Invoice')
@section('header', 'Template Invoice')
@section('subheader', 'Kelola template Markdown untuk PDF invoice')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Pilih template aktif, edit struktur Markdown, lalu download preview PDF.</p>
    <a href="{{ route('admin.invoices.templates.create') }}"
       class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
        + Template Baru
    </a>
</div>

<div class="grid gap-4">
    @forelse($templates as $template)
    <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center justify-between {{ $template->is_active ? 'ring-2 ring-blue-500' : '' }}">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-3">
                <h3 class="text-sm font-semibold text-gray-900">{{ $template->name }}</h3>
                @if($template->is_active)
                <span class="text-xs px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full font-medium">Aktif</span>
                @endif
            </div>
            @if($template->notes)
            <p class="text-xs text-gray-400 mt-1">{{ $template->notes }}</p>
            @endif
            <p class="text-xs text-gray-400 mt-0.5">
                Dibuat {{ $template->created_at->format('d M Y H:i') }}
                · {{ strlen($template->content ?? '') }} karakter
            </p>
        </div>

        <div class="flex items-center gap-3 flex-shrink-0 ml-4">
            <a href="{{ route('admin.invoices.templates.edit', $template) }}"
               class="text-xs px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                Edit
            </a>
            @if(!$template->is_active)
            <form method="POST" action="{{ route('admin.invoices.templates.activate', $template) }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="text-xs px-3 py-1.5 bg-gray-900 text-white rounded-lg hover:bg-gray-700 transition">
                    Aktifkan
                </button>
            </form>
            @endif
            <form method="POST" action="{{ route('admin.invoices.templates.destroy', $template) }}"
                  onsubmit="return confirm('Hapus template {{ $template->name }}?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-xs text-red-400 hover:text-red-600 transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
        <p class="text-sm text-gray-400">Belum ada template. Buat template baru atau gunakan default.</p>
    </div>
    @endforelse
</div>
@endsection