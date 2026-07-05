<header class="flex items-center justify-between h-14 px-6 bg-white border-b border-gray-100 flex-shrink-0">

    {{-- Toggle sidebar --}}
    <button
        @click="sidebarOpen = !sidebarOpen"
        class="text-gray-400 hover:text-gray-600 transition"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    {{-- Right side --}}
    <div class="flex items-center gap-3">

        @auth
        {{-- Notifikasi --}}
        <div class="relative" x-data="{ open: false }">
            <button
                @click="open = !open"
                class="relative text-gray-400 hover:text-gray-600 transition"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @php $unread = auth()->user()->unreadNotificationsCount(); @endphp
                @if($unread > 0)
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                    {{ $unread > 9 ? '9+' : $unread }}
                </span>
                @endif
            </button>

            {{-- Dropdown notifikasi --}}
            <div
                x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden"
            >
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900">Notifikasi</span>
                    <div class="flex items-center gap-2">
                        @if($unread > 0)
                        <form method="POST" action="{{ route('notifications.read-all') }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="text-xs text-blue-500 hover:text-blue-700 transition">
                                Baca Semua
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('notifications.index') }}"
                           class="text-xs text-gray-400 hover:text-gray-600 transition">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="max-h-72 overflow-y-auto">
                    @forelse(auth()->user()->notifications()->latest('created_at')->take(8)->get() as $notif)
                    <a href="{{ $notif->action_url ? route('notifications.read', $notif) : '#' }}"
                       class="block px-4 py-3 hover:bg-gray-50 transition
                              {{ !$notif->is_read ? 'bg-blue-50/40 border-l-2 border-blue-500' : '' }}">
                        <div class="flex items-start gap-3">
                            @php
                            $notifIcon = match($notif->type) {
                                'job_assigned'   => 'text-blue-500',
                                'job_review'     => 'text-yellow-500',
                                'job_done'       => 'text-green-500',
                                'invoice_sent'   => 'text-blue-500',
                                'invoice_paid'   => 'text-green-500',
                                'portfolio_published' => 'text-purple-500',
                                default          => 'text-gray-500',
                            };
                            @endphp
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $notif->title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $notif->message }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="px-4 py-6 text-center text-sm text-gray-400">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                        Tidak ada notifikasi
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="text-sm text-gray-500 hover:text-gray-900 transition px-2 py-1 rounded-lg hover:bg-gray-100">
                Keluar
            </button>
        </form>
        @endauth

    </div>
</header>