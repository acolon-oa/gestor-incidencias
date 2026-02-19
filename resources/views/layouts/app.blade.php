<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Apply theme as early as possible
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
    <style>
        /* Force high contrast for titles and main text in dark mode */
        [data-theme='dark'] .font-black,
        [data-theme='dark'] .font-bold,
        [data-theme='dark'] h1, 
        [data-theme='dark'] h2, 
        [data-theme='dark'] h3,
        [data-theme='dark'] .text-xl,
        [data-theme='dark'] .text-2xl,
        [data-theme='dark'] .text-3xl,
        [data-theme='dark'] .text-4xl {
            color: #ffffff !important;
        }

        /* Adjust opacities to be more visible in dark mode */
        [data-theme='dark'] .opacity-60 { opacity: 0.85 !important; color: #ffffff; }
        [data-theme='dark'] .opacity-70 { opacity: 0.9 !important; color: #ffffff; }
        [data-theme='dark'] .opacity-40 { opacity: 0.6 !important; color: #ffffff; }
        [data-theme='dark'] .opacity-50 { opacity: 0.75 !important; color: #ffffff; }
        
        /* Sidebar specific adjustments for dark mode */
        [data-theme='dark'] .drawer-side .menu p,
        [data-theme='dark'] .drawer-side .font-bold {
            color: #ffffff !important;
        }

        /* Table headers in dark mode */
        [data-theme='dark'] thead th {
            color: rgba(255, 255, 255, 0.7) !important;
        }
    </style>
</head>

<body class="bg-base-200 h-screen text-base-content transition-colors duration-300">
    <div class="drawer drawer-open">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" checked />

        <!-- MAIN CONTENT -->
        <div class="drawer-content flex flex-col px-10 py-6 overflow-y-auto">
            <x-navbar />

            @yield('content')
        </div>

        <x-sidebar />
    </div>
</body>
</html>
