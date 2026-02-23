@extends('layouts.guest')

@section('title', 'Verify Email')

@section('content')

    <h2 class="card-title text-center text-3xl font-bold justify-center">
        Email Verification
    </h2>
    <p class="text-center mb-10 opacity-70">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 text-center">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="mt-4 flex flex-col gap-4 items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full">
            @csrf
            <button type="submit" class="btn btn-primary w-full rounded-3xl">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="btn btn-ghost w-full rounded-3xl text-sm">
                Log Out
            </button>
        </form>
    </div>

@endsection
