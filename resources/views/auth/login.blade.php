@extends('layouts.guest')

@section('title', 'Login')

@section('content')

<h2 class="card-title text-center text-3xl font-bold justify-center">
    Welcome
</h2>
<p class="text-center mb-10">Log into your account</p>

@if (session('status'))
    <p class="text-center text-green-600 mb-3">
        {{ session('status') }}
    </p>
@endif

<form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
    @csrf

    <div class="form-control">
        <input type="email"
               name="email"
               placeholder="Email"
               value="{{ old('email') }}"
               autocomplete="username"
               required
               class="input input-md rounded-3xl w-full p-5 @error('email') input-error @enderror">
        @error('email')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-control">
        <input type="password"
               name="password"
               placeholder="Password"
               autocomplete="current-password"
               required
               class="input input-md rounded-3xl w-full p-5 @error('password') input-error @enderror">
        @error('password')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between mt-2">
        <label class="label cursor-pointer flex gap-2">
            <input type="checkbox" name="remember" class="toggle toggle-sm toggle-primary">
            <span class="label-text text-gray-500">Remember me</span>
        </label>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="link link-hover text-sm text-gray-500">
                Forgot password?
            </a>
        @endif
    </div>

    <div class="form-control mt-7">
        <button type="submit" class="btn btn-primary p-5 rounded-4xl w-full">
            Log in
        </button>
    </div>
</form>

@endsection
