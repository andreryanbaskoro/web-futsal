@extends('layouts.auth')

@section('content')

<h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">
    Login Akun
</h2>

{{-- Error Message --}}
@if ($errors->any())
    <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 rounded dark:bg-red-200">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    {{-- Email --}}
    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
            Email
        </label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-envelope"></i>
            </span>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="w-full pl-10 pr-3 py-2 border rounded-lg focus:ring focus:ring-blue-500
                       dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="email@example.com">
        </div>
    </div>

    {{-- Password --}}
    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
            Password
        </label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-lock"></i>
            </span>
            <input
                type="password"
                name="password"
                required
                class="w-full pl-10 pr-3 py-2 border rounded-lg focus:ring focus:ring-blue-500
                       dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="••••••••">
        </div>
    </div>

    {{-- Remember & Forgot --}}
    <div class="flex items-center justify-between text-sm">
        <label class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
            <input type="checkbox" name="remember"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            Remember me
        </label>

        <a href="{{ route('password.request') }}"
           class="text-blue-600 hover:underline dark:text-blue-400">
            Lupa password?
        </a>
    </div>

    {{-- Submit --}}
    <button type="submit"
        class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
        Login
    </button>
</form>

{{-- Register --}}
<div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-300">
    Belum punya akun?
    <a href="{{ route('register') }}"
       class="text-blue-600 hover:underline dark:text-blue-400 font-medium">
        Daftar sekarang
    </a>
</div>

@endsection
