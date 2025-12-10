<!doctype html>
<html lang="es" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password</title>
    @vite('resources/css/app.css')
</head>

<body class="flex items-center justify-center h-screen bg-base-300">

    <div class="w-full max-w-md bg-base-100 shadow-xl rounded-2xl p-8">

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
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
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>

                <input id="email" type="email" name="email"
                    class="input input-bordered w-full mt-1"
                    value="{{ old('email') }}" required autofocus>

                @error('email')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mt-6">

                {{-- Botón atrás --}}
                <a href="{{ route('login') }}" class="btn btn-outline rounded-2xl">
                    Back
                </a>

                {{-- Botón enviar --}}
                <button type="submit" class="btn btn-primary">
                    Email Password Reset Link
                </button>

            </div>

        </form>

    </div>

</body>
</html>
