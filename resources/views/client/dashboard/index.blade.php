<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Client Portal</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-2xl font-semibold text-gray-900">
            Halo, {{ auth()->user()->name }}!
        </h1>
        <p class="text-gray-500 mt-1">Client Portal</p>
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit"
                class="px-4 py-2 bg-gray-900 text-white text-sm rounded-lg hover:bg-gray-700">
                Logout
            </button>
        </form>
    </div>
</body>
</html>