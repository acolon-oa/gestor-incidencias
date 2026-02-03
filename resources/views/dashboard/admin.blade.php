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
            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2 mb-3 mt-3">
                <input type="text" name="ticket_id" value="{{ request('ticket_id') }}" placeholder="Ticket ID" class="input input-md input-bordered w-36" />
                <select name="status" class="select select-md select-bordered w-36">
                    <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All Status</option>
                    <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
                <button type="submit" class="btn btn-md btn-primary">Search</button>
                @if(request()->anyFilled(['ticket_id', 'status']))
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-md btn-ghost">Reset</a>
                @endif
            </form>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td>{{ $ticket->title }}</td>
                            <td>
                                @if($ticket->status == 'open')
                                    <span class="badge badge-error">Open</span>
                                @elseif($ticket->status == 'in_progress')
                                    <span class="badge badge-warning">In Progress</span>
                                @elseif($ticket->status == 'closed')
                                    <span class="badge badge-success">Closed</span>
                                @else
                                    <span class="badge badge-ghost">{{ ucfirst($ticket->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td>{{ $ticket->department ? $ticket->department->name : 'General' }}</td>
                            <td>
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-xs btn-ghost">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-400 py-8 italic">No tickets found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
