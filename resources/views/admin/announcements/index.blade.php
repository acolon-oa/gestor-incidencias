@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Announcements</h1>
            <p class="text-sm text-gray-500">Manage all announcements here.</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary shadow-lg shadow-primary/20 gap-2">
            <x-heroicon-o-plus-circle class="w-5 h-5" /> New
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
                        <th class="font-black uppercase text-[10px] tracking-widest">message</th>
                        <th class="font-black uppercase text-[10px] tracking-widest">type</th>
                        <th class="font-black uppercase text-[10px] tracking-widest">is active</th>
                        <th class="font-black uppercase text-[10px] tracking-widest pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($announcements as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="pl-6 font-bold text-gray-300">#{{ $item->id }}</td>
                            <td class="text-sm text-gray-500">{{ Str::limit($item->message, 50) }}</td>
                            <td class="text-sm text-gray-500">{{ Str::limit($item->type, 50) }}</td>
                            <td class="text-sm text-gray-500">{{ Str::limit($item->is_active, 50) }}</td>
                            <td class="pr-6 text-right">
                                <div class="flex justify-end gap-1">
                                    
                                    <a href="{{ route('admin.announcements.edit', $item->id) }}" class="btn btn-ghost btn-xs text-primary font-bold">Edit</a>
                                    <form action="{{ route('admin.announcements.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
        @if($announcements->hasPages())
            <div class="p-4 bg-gray-50/30 border-t border-gray-100">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>
@endsection
