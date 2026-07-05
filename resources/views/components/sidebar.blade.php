<aside
    class="flex flex-col w-56 min-w-56 bg-white border-r border-gray-100 h-screen transition-all duration-200 overflow-hidden"
    x-bind:style="sidebarOpen ? 'width: 224px; min-width: 224px;' : 'width: 0; min-width: 0;'"
>
    @php $role = auth()->check() ? auth()->user()->role->value : 'guest'; @endphp

    {{-- Logo --}}
    <div class="flex items-center h-14 px-4 border-b border-gray-100 flex-shrink-0 gap-2">
        <img src="{{ asset('images/logo.png') }}"
             alt="Storimax"
             class="h-8 w-auto object-contain">
        <span class="text-xs text-gray-400 font-medium">{{ $role === 'client' ? 'Portal' : ucfirst($role) }}</span>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">

        {{-- Dashboard --}}
        @if(in_array($role, ['admin', 'atasan']))
            <x-ui.nav-item
                href="{{ route('admin.dashboard') }}"
                icon="grid"
                label="Dashboard"
            />
        @elseif($role === 'crew')
            <x-ui.nav-item
                href="{{ route('crew.dashboard') }}"
                icon="grid"
                label="Dashboard"
            />
        @endif

        {{-- Admin & Atasan menu --}}
        @if(in_array($role, ['admin', 'atasan']))

            <div class="pt-3 pb-1 px-2">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Project</span>
            </div>

            <x-ui.nav-item
                href="{{ route('admin.projects.index') }}"
                icon="folder"
                label="Projects"
            />
            {{-- Jobs diarahkan ke projects dulu sampai modul jobs selesai --}}
            <x-ui.nav-item
            href="{{ route('admin.jobs.index') }}"
            icon="check-square"
            label="Jobs"
            />

            <div class="pt-3 pb-1 px-2">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Keuangan</span>
            </div>

            <x-ui.nav-item
                href="{{ route('admin.invoices.index') }}"
                icon="file-text"
                label="Invoice"
            />

            <div class="pt-3 pb-1 px-2">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Konten</span>
            </div>

            <x-ui.nav-item
                href="{{ route('admin.portfolios.index') }}"
                icon="image"
                label="Portofolio"
            />
            <x-ui.nav-item
                href="{{ route('admin.reports.index') }}"
                icon="bar-chart"
                label="Laporan"
            />

            @if($role === 'admin')
            <div class="pt-3 pb-1 px-2">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Manajemen</span>
            </div>

            <x-ui.nav-item
                href="{{ route('admin.users.index') }}"
                icon="users"
                label="Users"
            />
            <x-ui.nav-item
                href="{{ route('admin.clients.index') }}"
                icon="briefcase"
                label="Clients"
            />
            <x-ui.nav-item
                href="{{ route('admin.departments.index') }}"
                icon="layers"
                label="Departments"
            />
            @endif

        @endif

        {{-- Crew menu --}}
        @if($role === 'crew')
            <div class="pt-3 pb-1 px-2">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pekerjaan</span>
            </div>
            <x-ui.nav-item
                href="{{ route('crew.jobs.index') }}"
                icon="check-square"
                label="My Jobs"
            />
        @endif

        {{-- Client menu --}}
        @if($role === 'client')
            <div class="pt-3 pb-1 px-2">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Portal</span>
            </div>

            <x-ui.nav-item
                href="{{ route('client.dashboard') }}"
                icon="grid"
                label="Dashboard"
            />
            <x-ui.nav-item
                href="{{ route('client.invoices.index') }}"
                icon="file-text"
                label="Invoice"
            />
            <x-ui.nav-item
                href="{{ route('client.portfolios.index') }}"
                icon="image"
                label="Portofolio"
            />
        @endif

    </nav>

    {{-- User info --}}
@auth
<div class="border-t border-gray-100 p-3">
    <div class="flex items-center gap-3 px-2 py-2 rounded-lg">
        <div class="w-7 h-7 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
            <span class="text-xs font-medium text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->role->label() }}</p>
        </div>
    </div>
</div>
@endauth
</aside>