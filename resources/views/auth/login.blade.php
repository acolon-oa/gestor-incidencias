<!doctype html>
<html lang="es" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    @vite('resources/css/app.css')
</head>

<body class="flex items-center justify-center h-screen bg-blue-400">

    <div class="card w-96 shadow-xl bg-blue-100">
        <div class="card-body p-5">

            {{-- TÍTULO DEL LOGIN --}}
            <h2 class="card-title text-center text-3xl font-bold justify-center">Welcome</h2>
            <p class="text-center mb-10">Log into your account</p>

            {{-- Mensajes de estado generados por Laravel (p.ej. "Password reset enviado") --}}
            @if (session('status'))
                <p class="text-center text-green-600 mb-3">{{ session('status') }}</p>
            @endif

            {{-- FORMULARIO DE LOGIN --}}
            {{-- action="route('login')" → usa la ruta interna de Laravel para autenticar --}}
            {{-- method="POST" porque la autenticación siempre usa POST --}}
            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">

                {{-- Token CSRF: evita ataques Cross-Site Request Forgery. 
                     Laravel rechaza cualquier formulario sin esto. --}}
                @csrf

                {{-- CAMPO EMAIL --}}
                <div class="form-control">
                    {{-- old('email') repone el valor si el login falla --}}
                    {{-- autocomplete="username" ayuda a que el navegador rellene el email --}}
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                        autocomplete="username" required
                        class="input input-md rounded-3xl w-full p-5 @error('email') input-error @enderror">

                    {{-- Muestra los errores de validación para este campo --}}
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CAMPO PASSWORD --}}
                <div class="form-control">
                    <input type="password" name="password" placeholder="Password" autocomplete="current-password"
                        required class="input input-md rounded-3xl w-full p-5 @error('password') input-error @enderror">

                    {{-- Error de validación del password (si lo hay) --}}
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- OPCIONES: Remember me + Forgot password --}}
                <div class="flex items-center justify-between mt-2">
                    <!--FUNCIONALIDAD PENDIENTE DE IMPLEMENTAR-->
                    {{-- Remember me USARA una cookie para mantener la sesión activa --}}
                    <label class="label cursor-pointer flex gap-2">
                        <input type="checkbox" name="remember" class="toggle toggle-sm toggle-primary">
                        <span class="label-text text-gray-500">Remember me</span>
                    </label>

                    {{-- Enlace al formulario de "He olvidado mi contraseña" --}}
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="link link-hover text-sm text-gray-500">
                            Forgot password?
                        </a>
                    @endif
                </div>

                {{-- BOTÓN DE LOGIN --}}
                <div class="form-control mt-7">
                    <button type="submit" class="btn btn-primary p-5 rounded-4xl w-full">
                        Log in
                    </button>
                </div>

            </form>

        </div>
    </div>
</body>
</html>
