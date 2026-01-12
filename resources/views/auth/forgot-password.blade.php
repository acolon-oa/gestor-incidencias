@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')

<div class="mb-4 text-sm">
    Forgot your password? No problem. Just let us know your email address and we will email you a password reset
    link that will allow you to choose a new one.
</div>

{{-- Session Status --}}
@if (session('status'))
    <div class="mb-4 text-green-600 text-sm">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    {{-- Email --}}
    <div class="mb-4">
        <label for="email" class="block text-sm font-medium">Email</label>

        <input id="email"
               type="email"
               name="email"
               value="{{ old('email') }}"
               required
               autofocus
               class="input input-bordered w-full mt-1">

        @error('email')
            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between mt-6">

        {{-- Back --}}
        <a href="{{ route('login') }}" class="btn btn-outline rounded-2xl">
            Back
        </a>

        {{-- Submit --}}
        <button type="submit" class="btn btn-primary">
            Email Password Reset Link
        </button>

    </div>
</form>

@endsection
