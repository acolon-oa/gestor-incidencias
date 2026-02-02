@php
    // Obtenemos la ruta actual
    $currentRoute = Route::currentRouteName();

    // Solo mostrar si estamos en algún dashboard (user o admin)
    $showDashboardNav = str_ends_with($currentRoute, '.dashboard');

    // Determinar ruta de crear ticket según rol
    $routeName = auth()->user()->hasRole('admin') 
        ? 'admin.tickets.create' 
        : 'user.tickets.create';
@endphp

@if($showDashboardNav)
<nav class="flex items-center justify-between mb-6">
    <div class="text-xl font-bold">
        Welcome, {{ auth()->user()->name }}!
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route($routeName) }}" class="btn btn-primary flex items-center gap-2">
            <x-heroicon-o-plus class="w-5 h-5" />
            New Ticket
        </a>
    </div>
</nav>
@endif
