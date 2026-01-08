@extends('layouts.auth')

@section('content')
<h2 class="text-2xl font-bold text-center mb-6">
    Lupa Password
</h2>

@if (session('status'))
    <div class="mb-4 text-green-600 text-sm">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
    @csrf

    <input type="email" name="email" placeholder="Email"
        class="w-full px-4 py-2 border rounded-lg" required>

    @error('email')
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror

    <button class="w-full py-2 bg-blue-600 text-white rounded-lg">
        Kirim Link Reset Password
    </button>
</form>
@endsection
