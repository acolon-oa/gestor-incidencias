@extends('layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="card bg-base-100 shadow-sm border border-gray-200 p-8 rounded-2xl max-w-4xl mx-auto mt-10">
    <div class="flex justify-between items-start mb-8 pb-4 border-b border-gray-100">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $user->name }}</h1>
            <p class="text-gray-500">{{ $user->email }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">Edit User</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Account Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400">Department</label>
                    <p class="font-bold text-gray-700">{{ $user->department->name ?? 'None' }}</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-400">Role</label>
                    <div class="mt-1 flex gap-1">
                        @foreach($user->roles as $role)
                            <span class="badge badge-primary font-bold">{{ strtoupper($role->name) }}</span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-400">Creation Date</label>
                    <p class="font-bold text-gray-700">{{ $user->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Activity Statistics</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-xl">
                    <div class="text-2xl font-black text-primary">{{ $user->tickets()->count() }}</div>
                    <div class="text-[10px] font-bold uppercase text-gray-400">Tickets Reported</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl">
                    <div class="text-2xl font-black text-success">{{ $user->assignedTickets()->count() }}</div>
                    <div class="text-[10px] font-bold uppercase text-gray-400">Assigned Tickets</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
