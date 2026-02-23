@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')

    <h2 class="card-title text-center text-3xl font-bold justify-center">
        Reset Password
    </h2>
    <p class="text-center mb-10">Enter your new password below</p>

    <form method="POST" action="{{ route('password.store') }}" class="flex flex-col gap-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-control">
            <input type="email"
                   name="email"
                   placeholder="Email"
                   value="{{ old('email', $request->email) }}"
                   required
                   autofocus
                   class="input input-md rounded-3xl w-full p-5 @error('email') input-error @enderror">
            @error('email')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-control">
            <input type="password"
                   name="password"
                   placeholder="New Password"
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
                   placeholder="Confirm New Password"
                   required
                   class="input input-md rounded-3xl w-full p-5">
        </div>

        <div class="form-control mt-7">
            <button type="submit" class="btn btn-primary p-5 rounded-4xl w-full">
                Reset Password
            </button>
        </div>
    </form>

@endsection
