<!doctype html>
<html lang="es" data-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Auth')</title>

    @vite('resources/css/app.css')
    <script>
        // Aplicar el tema guardado o por defecto light
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
</head>

<body class="flex items-center justify-center h-screen bg-base-200 transition-colors duration-300">

    <!-- Theme Toggle Floating Button -->
    <div class="fixed top-6 right-6 z-50">
        <button onclick="toggleTheme()" class="btn btn-ghost btn-circle btn-sm shadow-sm bg-base-100 hover:bg-base-200" title="Change Theme">
            <x-heroicon-o-sun class="w-5 h-5 block dark:hidden" />
            <x-heroicon-o-moon class="w-5 h-5 hidden dark:block" />
        </button>
    </div>

    <div class="flex flex-col items-center gap-6">

        {{-- LOGO --}}
        <!-- Logo Modo Claro -->
        <img src="{{ asset('images/velox.png') }}" alt="Logo" class="w-100 mb-10 block dark:hidden">
        <!-- Logo Modo Noche -->
        <img src="{{ asset('images/velox-blanco.png') }}" alt="Logo" class="w-100 mb-10 hidden dark:block">

        {{-- CARD --}}
        <div class="card w-96 shadow-xl bg-base-100 p-5 scale-115 mb-40 border border-base-content/5">
            <div class="card-body p-5">

                @yield('content')

            </div>
        </div>

    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }
    </script>

</body>
</html>
