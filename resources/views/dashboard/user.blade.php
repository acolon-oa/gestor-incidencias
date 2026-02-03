@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')

    <!-- Ticket Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 mt-5">
        <div class="card bg-base-100 shadow p-4">
            <div class="text-sm text-gray-500">Open Tickets</div>
            <div class="text-2xl font-bold">{{ $openTicketsCount }}</div>
        </div>
        <div class="card bg-base-100 shadow p-4">
            <div class="text-sm text-gray-500">In Progress</div>
            <div class="text-2xl font-bold">{{ $inProgressTicketsCount }}</div>
        </div>
        <div class="card bg-base-100 shadow p-4">
            <div class="text-sm text-gray-500">Resolved</div>
            <div class="text-2xl font-bold">{{ $resolvedTicketsCount }}</div>
        </div>
    </div>

    <!-- Incidences Card -->
    <div class="card bg-base-100 shadow p-4 mt-2">
        <div class="flex justify-between items-center mb-4">
            <div class="text-md font-bold ml-3 text-gray-500">My Tickets</div>
            <div class="flex items-center gap-2 mb-3 mt-3">
                <a href="{{ route('user.tickets.create') }}" class="btn btn-md btn-primary">New Ticket</a>
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
                                @endif
                            </td>
                            <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td>{{ $ticket->department->name }}</td>
                            <td>
                                <a href="{{ route('user.tickets.show', $ticket->id) }}" class="btn btn-xs btn-ghost">Details</a>
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
