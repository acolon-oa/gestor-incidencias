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
            <div class="text-md font-bold ml-3 text-gray-500">Departmental & My Tickets</div>
            <div class="flex items-center gap-2 mb-3 mt-3">
                <a href="{{ route('user.tickets.create') }}" class="btn btn-md btn-primary">New Ticket</a>
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
                        <tr class="hover:bg-base-200 transition-colors cursor-pointer" onclick="window.location='{{ route('user.tickets.show', $ticket->id) }}'">
                            <td class="font-bold text-gray-400">#{{ $ticket->id }}</td>
                            <td class="font-semibold text-gray-700">{{ $ticket->title }}</td>
                            <td>
                                @if($ticket->status == 'open')
                                    <span class="badge badge-error">Open</span>
                                @elseif($ticket->status == 'in_progress')
                                    <span class="badge badge-warning">In Progress</span>
                                @elseif($ticket->status == 'closed')
                                    <span class="badge badge-success">Closed</span>
                                @endif
                            </td>
                            <td class="text-sm text-gray-500">{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td>
                                <span class="badge badge-ghost">{{ $ticket->department->name }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-400 py-8 italic">No tickets found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
