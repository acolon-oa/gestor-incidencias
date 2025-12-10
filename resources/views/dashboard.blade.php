<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-base-200 h-screen text-base">
    <div class="drawer drawer-open">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" checked />

        <!-- MAIN CONTENT -->
        <div class="drawer-content flex flex-col px-10 py-6">

            <!-- Navbar -->
            <nav class="flex items-center justify-between mb-6">
                <div class="text-xl font-bold">Welcome back, Admin</div>
                <div class="flex items-center gap-3">
                    <button class="btn btn-primary flex items-center gap-2">
                        <x-heroicon-o-plus class="w-5 h-5" />
                        <p>New Ticket</p>
                    </button>
                </div>
            </nav>

            <!-- Ticket Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 mt-5">
                <div class="card bg-base-100 shadow p-4">
                    <div class="text-sm text-gray-500">Open Tickets</div>
                    <div class="text-2xl font-bold">3</div>
                </div>
                <div class="card bg-base-100 shadow p-4">
                    <div class="text-sm text-gray-500">Tickets Pending My Action</div>
                    <div class="text-2xl font-bold">1</div>
                </div>
                <div class="card bg-base-100 shadow p-4">
                    <div class="text-sm text-gray-500">Recently Resolved Tickets</div>
                    <div class="text-2xl font-bold">5</div>
                </div>
            </div>

            <!-- Incidences Card -->
            <div class="card bg-base-100 shadow p-4 mt-2">
                <div class="flex justify-between items-center mb-4">
                    <div class="text-md font-bold ml-3 text-gray-500">All Tickets</div>
                    <div class="flex items-center gap-2 mb-3 mt-3">
                        <input type="text" placeholder="Ticket ID" class="input input-md input-bordered w-36" />
                        <select class="select select-md select-bordered w-36">
                            <option disabled selected>Status</option>
                            <option>All</option>
                            <option>Open</option>
                            <option>Pending</option>
                            <option>In Progress</option>
                            <option>Resolved</option>
                        </select>
                        <button class="btn btn-md btn-primary">Search</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#9145</td>
                                <td>Marketing Printer Issue</td>
                                <td><span class="badge badge-error">Open</span></td>
                                <td>15m ago</td>
                                <td>Marketing</td>
                            </tr>
                            <tr>
                                <td>#9144</td>
                                <td>Maintenance Material Request</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                                <td>2h ago</td>
                                <td>Maintenance</td>
                            </tr>
                            <tr>
                                <td>#9142</td>
                                <td>Finance Software Error</td>
                                <td><span class="badge badge-error">Open</span></td>
                                <td>1d ago</td>
                                <td>Finance</td>
                            </tr>
                            <tr>
                                <td>#9130</td>
                                <td>Need access to Sales shared folder</td>
                                <td><span class="badge badge-info">In Progress</span></td>
                                <td>3d ago</td>
                                <td>Sales</td>
                            </tr>
                            <tr>
                                <td>#9125</td>
                                <td>Cannot connect to VPN</td>
                                <td><span class="badge badge-success">Resolved</span></td>
                                <td>5d ago</td>
                                <td>IT General</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="drawer-side">
            <div class="flex min-h-full flex-col bg-base-100 w-64 p-4 text-base">

                <section class="flex items-center mb-8 p-2">
                    <img src="{{ asset('images/laravel.svg') }}" alt="Logo" class="w-10 h-10 mr-3">
                    <div class="text-2xl font-bold text-gray-800">Dashboard</div>
                </section>

                <ul class="menu w-full grow text-md">
                    <li class="mb-3">
                        <a href="{{ route('dashboard') }} "class="flex items-center gap-2 active hover:bg-gray-100 rounded-md p-3">
                            <x-heroicon-o-home class="w-5 h-5" />
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="mb-3">
                        <a class="flex items-center gap-2 hover:bg-gray-100 rounded-md p-3">
                            <x-heroicon-o-inbox class="w-5 h-5" />
                            <p>Tickets</p> <span class="badge badge-primary">3</span>
                        </a>
                    </li>
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
                    <li class="mt-4 mb-3">
                        <a class="flex items-center gap-2 hover:bg-gray-100 rounded-md p-3">
                            <x-heroicon-o-bell class="w-5 h-5" />
                            <p>Notifications</p> <span class="badge badge-secondary">2</span>
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
                        <img src="{{ asset('images/example-pfp.svg') }}" alt="Avatar" class="rounded-full w-10 h-10">
                        <div>
                            <div class="font-bold">Administrator</div>
                            <div class="text-sm text-gray-500">Admin</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-circle p-2 hover:bg-red-100">
                            <x-heroicon-o-arrow-right-on-rectangle class="w-6 h-6 text-red-500" />
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</body>

</html>
