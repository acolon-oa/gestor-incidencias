@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">System Users</h1>
            <p class="text-sm text-gray-500">Manage all registered users and their access levels.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-lg shadow-primary/20 gap-2">
            <x-heroicon-o-plus-circle class="w-5 h-5" /> New User
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-6 shadow-sm border-none bg-green-50 text-green-800">
            <x-heroicon-o-check-circle class="w-6 h-6" />
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error mb-6 shadow-sm border-none bg-red-50 text-red-800">
            <x-heroicon-o-x-circle class="w-6 h-6" />
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="card bg-base-100 shadow-sm border border-gray-100 overflow-hidden rounded-2xl">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400">
                        <th class="font-black uppercase text-[10px] tracking-widest pl-6">ID</th>
                        <th class="font-black uppercase text-[10px] tracking-widest">Full Name</th>
                        <th class="font-black uppercase text-[10px] tracking-widest">Email</th>
                        <th class="font-black uppercase text-[10px] tracking-widest">Department</th>
                        <th class="font-black uppercase text-[10px] tracking-widest">Role</th>
                        <th class="font-black uppercase text-[10px] tracking-widest pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="pl-6 font-bold text-gray-300">#{{ $user->id }}</td>
                            <td class="font-bold text-gray-800">{{ $user->name }}</td>
                            <td class="text-sm text-gray-500">{{ $user->email }}</td>
                            <td>
                                <span class="badge badge-ghost font-medium px-3 py-1 rounded-full">{{ $user->department->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge badge-primary badge-sm font-bold">{{ strtoupper($role->name) }}</span>
                                @endforeach
                            </td>
                            <td class="pr-6 text-right">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-ghost btn-xs text-primary font-bold">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs text-error font-bold">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="p-4 bg-gray-50/30 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
