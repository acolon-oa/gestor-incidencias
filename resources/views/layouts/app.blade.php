<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-base-200 h-screen text-base">
    <div class="drawer drawer-open">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" checked />

        <!-- MAIN CONTENT -->
        <div class="drawer-content flex flex-col px-10 py-6">
            <x-navbar />

            @yield('content')
        </div>

        <x-sidebar />
    </div>
</body>
</html>
