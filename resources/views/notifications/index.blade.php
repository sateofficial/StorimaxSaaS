@extends('layouts.app')

@section('title', 'Notifikasi')
@section('header', 'Notifikasi')
@section('subheader', 'Riwayat notifikasi dan aktivitas')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
        @if($unreadCount > 0)
        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            @method('PATCH')
            <button type="submit"
                    class="text-xs px-4 py-2 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-700 transition">
                Tandai Semua Dibaca ({{ $unreadCount }})
            </button>
        </form>
        @endif
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">

    @if($notifications->isEmpty())
    <div class="py-16 text-center text-sm text-gray-400">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        Belum ada notifikasi.
    </div>
    @else
    <div class="divide-y divide-gray-50">
        @foreach($notifications as $notif)
        <div class="px-5 py-4 flex items-start justify-between gap-4
                    {{ !$notif->is_read ? 'bg-blue-50/30' : 'hover:bg-gray-50' }} transition">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    @if(!$notif->is_read)
                    <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                    @endif
                    <p class="text-sm font-medium text-gray-900">{{ $notif->title }}</p>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ $notif->message }}</p>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-xs text-gray-400">{{ $notif->created_at->format('d M Y H:i') }}</span>
                    <span class="text-xs text-gray-300">·</span>
                    <span class="text-xs capitalize text-gray-400">{{ str_replace('_', ' ', $notif->type) }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                @if($notif->action_url)
                <a href="{{ route('notifications.read', $notif) }}"
                   class="text-xs text-blue-500 hover:text-blue-700 transition">
                    Lihat
                </a>
                @elseif(!$notif->is_read)
                <form method="POST" action="{{ route('notifications.read', $notif) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="text-xs text-gray-400 hover:text-gray-700 transition">
                        Tandai Dibaca
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route('notifications.destroy', $notif) }}"
                      onsubmit="return confirm('Hapus notifikasi ini?')">
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

    {{-- Pagination --}}
    <div class="px-5 py-3 border-t border-gray-50">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

@endsection
