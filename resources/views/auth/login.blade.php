@extends('layouts.auth')

@section('content')
<div class="w-full max-w-md px-6">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}"
             alt="Storimax"
             class="h-12 w-auto mx-auto mb-3 object-contain">
        <p class="text-sm text-gray-500">Story in Motion. Maxed to Perfection.</p>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <h2 class="text-lg font-medium text-gray-800 mb-6">Masuk ke sistem</h2>

        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-100 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="nama@storimax.id"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent
                           placeholder:text-gray-300 transition"
                >
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    placeholder="••••••••"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent
                           placeholder:text-gray-300 transition"
                >
            </div>

            {{-- Remember --}}
            <div class="flex items-center gap-2">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    class="rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                >
                <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="w-full py-2 px-4 bg-gray-900 hover:bg-gray-700 text-white text-sm
                       font-medium rounded-lg transition focus:outline-none focus:ring-2
                       focus:ring-gray-900 focus:ring-offset-2 mt-2"
            >
                Masuk
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-gray-400 mt-6">
        © {{ date('Y') }} PT Jalur Tengah Kreasindo
    </p>
</div>
@endsection