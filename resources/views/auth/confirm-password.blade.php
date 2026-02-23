@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('content')

    <h2 class="card-title text-center text-3xl font-bold justify-center">
        Secure Area
    </h2>
    <p class="text-center mb-10 opacity-70 text-sm">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="flex flex-col gap-4">
        @csrf

        <!-- Password -->
        <div class="form-control">
            <input type="password"
                   name="password"
                   placeholder="Password"
                   required
                   autocomplete="current-password"
                   class="input input-md rounded-3xl w-full p-5 @error('password') input-error @enderror">
            @error('password')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="btn btn-primary w-full rounded-3xl">
                Confirm
            </button>
        </div>
    </form>

@endsection
