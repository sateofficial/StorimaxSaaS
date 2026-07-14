<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main area --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            {{-- Topbar --}}
            @include('components.topbar')

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto p-6">

                @hasSection('header')
                <div class="mb-6">
                    <h1 class="text-xl font-semibold text-gray-900">@yield('header')</h1>
                    @hasSection('subheader')
                    <p class="text-sm text-gray-500 mt-0.5">@yield('subheader')</p>
                    @endif
                </div>
                @endif

                @if(session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-100 text-sm text-green-700"
                     x-data="{ show: true }" x-show="show"
                     x-init="setTimeout(() => show = false, 4000)">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-100 text-sm text-red-700"
                     x-data="{ show: true }" x-show="show"
                     x-init="setTimeout(() => show = false, 4000)">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>