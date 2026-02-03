<div class="drawer-side">
    <div class="flex min-h-full flex-col bg-base-100 w-64 p-4 text-base">

        <section class="flex items-center mb-8 p-2">
            <img src="{{ asset('images/laravel.svg') }}" alt="Logo" class="w-10 h-10 mr-3">
            <div class="text-2xl font-bold">Dashboard</div>
        </section>

        <ul class="menu w-full grow text-md">

            <li class="mb-3">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2 {{ request()->routeIs('dashboard*') ? 'active' : '' }} hover:bg-gray-100 rounded-md p-3">
                    <x-heroicon-o-home class="w-5 h-5" />
                    <p>Dashboard</p>
                </a>
            </li>

            <li class="mb-3">
                <a class="flex items-center gap-2 hover:bg-gray-100 rounded-md p-3">
                    <x-heroicon-o-inbox class="w-5 h-5" />
                    <p>Tickets</p>
                </a>
            </li>

            {{-- SOLO ADMIN --}}
            @role('admin')
            <li class="mb-3">
                <a class="flex items-center gap-2 hover:bg-gray-100 rounded-md p-3">
                    <x-heroicon-o-chart-bar class="w-5 h-5" />
                    <p>Statistics</p>
                </a>
            </li>

            <li class="mb-3">
                <a class="flex items-center gap-2 hover:bg-gray-100 rounded-md p-3">
                    <x-heroicon-o-users class="w-5 h-5" />
                    <p>Users</p>
                </a>
            </li>
            @endrole

            <li class="mt-4 mb-3">
                <a class="flex items-center gap-2 hover:bg-gray-100 rounded-md p-3">
                    <x-heroicon-o-bell class="w-5 h-5" />
                    <p>Notifications</p>
                </a>
            </li>

            <li class="mb-3">
                <a class="flex items-center gap-2 hover:bg-gray-100 rounded-md p-3">
                    <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                    <p>Settings</p>
                </a>
            </li>

        </ul>

        <hr class="border-t border-gray-200 my-4">

        <div class="mt-auto flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/example-pfp.svg') }}" alt="Avatar"
                     class="rounded-full w-10 h-10">
                <div>
                    <div class="font-bold">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ auth()->user()->getRoleNames()->first() }}
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="btn btn-ghost btn-circle p-2 hover:bg-red-100">
                    <x-heroicon-o-arrow-right-on-rectangle
                        class="w-6 h-6 text-red-500" />
                </button>
            </form>
        </div>

    </div>
</div>
