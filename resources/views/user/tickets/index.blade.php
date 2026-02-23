@extends('layouts.app')

@section('title', 'My Tickets')

@section('content')
<div class="w-full max-w-screen-2xl mx-auto py-8 px-4">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Tickets</h1>
            <p class="text-sm text-gray-500">All tickets you have submitted.</p>
        </div>
        <a href="{{ route('user.tickets.create') }}" class="btn btn-primary">New Ticket</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="card bg-base-100 shadow border border-gray-200 overflow-hidden rounded-2xl">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="bg-gray-50 text-gray-400">
                        <th class="font-black uppercase text-[10px] tracking-widest py-4 text-center">ID</th>
                        <th class="font-black uppercase text-[10px] tracking-widest py-4">Subject</th>
                        <th class="font-black uppercase text-[10px] tracking-widest py-4">Department</th>
                        <th class="font-black uppercase text-[10px] tracking-widest py-4">Status</th>
                        <th class="font-black uppercase text-[10px] tracking-widest py-4">Last Updated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer"
                            onclick="window.location='{{ route('user.tickets.show', $ticket->id) }}'">
                            <td class="font-bold text-gray-400 text-center">#{{ $ticket->id }}</td>
                            <td>
                                <div class="font-bold text-gray-800">{{ $ticket->title }}</div>
                                <div class="text-[10px] uppercase font-bold tracking-tight {{ 
                                    $ticket->priority == 'urgent' ? 'text-error' : (
                                    $ticket->priority == 'high' ? 'text-warning' : (
                                    $ticket->priority == 'medium' ? 'text-info' : 'text-gray-400'))
                                }}">{{ $ticket->priority }} priority</div>
                            </td>
                            <td>
                                <span class="badge badge-ghost badge-sm">{{ $ticket->department->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if($ticket->status == 'open')
                                    <span class="badge badge-error badge-sm font-bold">OPEN</span>
                                @elseif($ticket->status == 'in_progress')
                                    <span class="badge badge-warning badge-sm font-bold">IN PROGRESS</span>
                                @elseif($ticket->status == 'closed')
                                    <span class="badge badge-success badge-sm font-bold text-white">CLOSED</span>
                                @endif
                            </td>
                            <td class="text-xs text-gray-500">{{ $ticket->updated_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-400 italic">
                                You haven't submitted any tickets yet.
                                <a href="{{ route('user.tickets.create') }}" class="text-primary font-bold">Create one now</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
