@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    <!-- Ticket Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 mt-5">
        <div class="card bg-base-100 shadow p-4">
            <div class="text-sm text-gray-500">Open Tickets</div>
            <div class="text-2xl font-bold">{{ $openTicketsCount }}</div>
        </div>
        <div class="card bg-base-100 shadow p-4">
            <div class="text-sm text-gray-500">Tickets Pending My Action</div>
            <div class="text-2xl font-bold">{{ $myPendingTicketsCount }}</div>
        </div>
        <div class="card bg-base-100 shadow p-4">
            <div class="text-sm text-gray-500">Recently Resolved Tickets</div>
            <div class="text-2xl font-bold">{{ $resolvedTicketsCount }}</div>
        </div>
    </div>

    <!-- Incidences Card -->
    <div class="card bg-base-100 shadow p-4 mt-2">
        <div class="flex justify-between items-center mb-4">
            <div class="text-md font-bold ml-3 text-gray-500">All Tickets</div>
            <div class="flex items-center gap-2 mb-3 mt-3">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2">
                    <input type="text" name="ticket_id" value="{{ request('ticket_id') }}" placeholder="Ticket ID" class="input input-md input-bordered w-36" />
                    <select name="status" class="select select-md select-bordered w-36">
                        <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Resolved</option>
                    </select>
                    <button type="submit" class="btn btn-md btn-primary">Search</button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto overflow-y-auto max-h-[500px]">
            <table class="table w-full table-pin-rows">
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
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-base-200 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.tickets.show', $ticket->id) }}'">
                        <td>#{{ $ticket->id }}</td>
                        <td class="font-medium">{{ $ticket->title }}</td>
                        <td>
                            @if($ticket->status == 'open')
                                <span class="badge badge-error">Open</span>
                            @elseif($ticket->status == 'in_progress')
                                <span class="badge badge-warning">In Progress</span>
                            @else
                                <span class="badge badge-success">Resolved</span>
                            @endif
                        </td>
                        <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td>{{ $ticket->department->name ?? 'General' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 opacity-50 italic">No tickets found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
