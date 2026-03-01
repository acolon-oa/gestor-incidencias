<div class="drawer-side h-screen">
    <div class="flex min-h-full flex-col bg-base-100 w-64 p-4 text-base border-r border-base-content/5">

        <section class="flex items-center mb-8 p-2">
            <img src="{{ asset('images/laravel.svg') }}" alt="Logo" class="w-10 h-10 mr-3">
            <div class="text-2xl font-bold text-base-content">HelpDesk</div>
        </section>

        <ul class="menu w-full grow text-md p-0">

            <li class="mb-1">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 rounded-xl p-3 font-semibold transition-colors
                   {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                    <x-heroicon-o-home class="w-5 h-5" />
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="mb-1">
                <a href="{{ auth()->user()->hasRole('admin') ? route('admin.tickets.index') : route('user.dashboard') }}"
                   class="flex items-center gap-3 rounded-xl p-3 font-semibold transition-colors
                   {{ request()->routeIs('admin.tickets.*') || request()->routeIs('user.tickets.*') ? 'bg-primary/10 text-primary' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                    <x-heroicon-o-inbox class="w-5 h-5" />
                    <span>Tickets</span>
                </a>
            </li>

            {{-- SOLO ADMIN --}}
            @role('admin')
            <li class="mb-1">
                <a href="{{ route('admin.statistics.index') }}"
                   class="flex items-center gap-3 rounded-xl p-3 font-semibold transition-colors
                   {{ request()->routeIs('admin.statistics.*') ? 'bg-primary/10 text-primary' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                    <x-heroicon-o-chart-bar class="w-5 h-5" />
                    <span>Statistics</span>
                </a>
            </li>

            <li class="mb-1">
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 rounded-xl p-3 font-semibold transition-colors
                   {{ request()->routeIs('admin.users.*') ? 'bg-primary/10 text-primary' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                    <x-heroicon-o-users class="w-5 h-5" />
                    <span>Users</span>
                </a>
            </li>
            @endrole

            <li class="mt-2 mb-1">
                @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                <a href="{{ route('notifications.index') }}"
                   class="flex items-center gap-3 rounded-xl p-3 font-semibold transition-colors
                   {{ request()->routeIs('notifications.*') ? 'bg-primary/10 text-primary' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                    <x-heroicon-o-bell class="w-5 h-5" />
                    <span>Notifications</span>
                    @if($unreadCount > 0)
                        <span class="ml-auto badge badge-primary badge-sm font-black min-w-[1.4rem]">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                    @endif
                </a>
            </li>

            <li class="mb-1">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 rounded-xl p-3 font-semibold transition-colors
                   {{ request()->routeIs('profile.edit') ? 'bg-primary/10 text-primary' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                    <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                    <span>Settings</span>
                </a>
            </li>

        </ul>

        <hr class="border-t border-base-content/5 my-3">

        <div class="flex items-center justify-between gap-3 p-2 bg-base-200/50 rounded-2xl mb-2">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black text-sm flex-shrink-0 animate-pulse">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="font-bold text-sm leading-tight text-base-content truncate">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="text-[10px] text-base-content/40 capitalize font-bold tracking-wider">
                        {{ auth()->user()->getRoleNames()->first() }}
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                @csrf
                <button type="submit"
                        class="btn btn-ghost btn-circle btn-sm hover:bg-red-500/10 group" title="Logout">
                    <x-heroicon-o-arrow-right-on-rectangle
                        class="w-4 h-4 text-base-content/40 group-hover:text-red-500 transition-colors" />
                </button>
            </form>
        </div>

    </div>
</div>
