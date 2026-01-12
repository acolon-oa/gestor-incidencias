<!doctype html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Auth')</title>

    @vite('resources/css/app.css')
</head>

<body class="flex items-center justify-center h-screen bg-base-300">

    <div class="flex flex-col items-center gap-6">

        {{-- LOGO --}}
        <img src="{{ asset('images/laravel.svg') }}" alt="Logo" class="size-32 mb-10">

        {{-- CARD --}}
        <div class="card w-96 shadow-xl bg-base-100 p-5 scale-115 mb-40">
            <div class="card-body p-5">

                @yield('content')

            </div>
        </div>

    </div>

</body>
</html>
