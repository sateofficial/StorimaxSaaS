@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subheader', 'Selamat datang kembali, ' . auth()->user()->name)

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

    {{-- Stat cards placeholder --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Projects</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">0</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Jobs Aktif</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">0</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Invoice Pending</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">0</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Crew</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">0</p>
    </div>

</div>
@endsection