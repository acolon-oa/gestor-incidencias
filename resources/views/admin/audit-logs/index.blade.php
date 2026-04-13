@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Audit Logs</h1>
            <p class="text-sm text-gray-500">Manage all audit logs here.</p>
        </div>
        
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
                        <th class="font-black uppercase text-[10px] tracking-widest">type</th>
                        <th class="font-black uppercase text-[10px] tracking-widest">old value</th>
                        <th class="font-black uppercase text-[10px] tracking-widest">new value</th>
                        <th class="font-black uppercase text-[10px] tracking-widest">created at</th>
                        <th class="font-black uppercase text-[10px] tracking-widest pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($audit_logs as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="pl-6 font-bold text-gray-300">#{{ $item->id }}</td>
                            <td class="text-sm text-gray-500">{{ Str::limit($item->type, 50) }}</td>
                            <td class="text-sm text-gray-500">{{ Str::limit($item->old_value, 50) }}</td>
                            <td class="text-sm text-gray-500">{{ Str::limit($item->new_value, 50) }}</td>
                            <td class="text-sm text-gray-500">{{ Str::limit($item->created_at, 50) }}</td>
                            <td class="pr-6 text-right">
                                <div class="flex justify-end gap-1">
                                    
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($audit_logs->hasPages())
            <div class="p-4 bg-gray-50/30 border-t border-gray-100">
                {{ $audit_logs->links() }}
            </div>
        @endif
    </div>
@endsection
