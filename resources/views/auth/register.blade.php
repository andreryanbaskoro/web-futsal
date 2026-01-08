@extends('layouts.auth')

@section('content')
<h2 class="text-2xl font-bold text-center mb-6">Register</h2>

<form method="POST" action="{{ route('register.store') }}" class="space-y-4">
    @csrf

    <input type="text" name="name" placeholder="Nama"
        class="w-full px-4 py-2 border rounded-lg" required>

    <input type="email" name="email" placeholder="Email"
        class="w-full px-4 py-2 border rounded-lg" required>

    <input type="password" name="password" placeholder="Password"
        class="w-full px-4 py-2 border rounded-lg" required>

    <input type="password" name="password_confirmation"
        placeholder="Konfirmasi Password"
        class="w-full px-4 py-2 border rounded-lg" required>

    <button class="w-full py-2 bg-blue-600 text-white rounded-lg">
        Daftar
    </button>
</form>
@endsection
