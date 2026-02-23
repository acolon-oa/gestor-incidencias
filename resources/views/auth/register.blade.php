@extends('layouts.guest')

@section('title', 'Register')

@section('content')

    <h2 class="card-title text-center text-3xl font-bold justify-center">
        Create Account
    </h2>
    <p class="text-center mb-10">Join the incident management system</p>

    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
        @csrf

        <!-- Name -->
        <div class="form-control">
            <input type="text"
                   name="name"
                   placeholder="Full Name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   class="input input-md rounded-3xl w-full p-5 @error('name') input-error @enderror">
            @error('name')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-control">
            <input type="email"
                   name="email"
                   placeholder="Email"
                   value="{{ old('email') }}"
                   required
                   class="input input-md rounded-3xl w-full p-5 @error('email') input-error @enderror">
            @error('email')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-control">
            <input type="password"
                   name="password"
                   placeholder="Password"
                   required
                   autocomplete="new-password"
                   class="input input-md rounded-3xl w-full p-5 @error('password') input-error @enderror">
            @error('password')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-control">
            <input type="password"
                   name="password_confirmation"
                   placeholder="Confirm Password"
                   required
                   class="input input-md rounded-3xl w-full p-5">
        </div>

        <div class="flex items-center justify-end mt-4">
            <a href="{{ route('login') }}" class="link link-hover text-sm text-gray-500">
                Already registered?
            </a>
        </div>

        <div class="form-control mt-7">
            <button type="submit" class="btn btn-primary p-5 rounded-4xl w-full">
                Register
            </button>
        </div>
    </form>

@endsection
