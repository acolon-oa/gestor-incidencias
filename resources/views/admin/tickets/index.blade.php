@extends('layouts.app')

@section('title', 'All Tickets')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Ticket Repository</h1>
            <p class="text-sm text-gray-500">View and manage all tickets across the organization.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <!-- Bulk Actions (Hidden by default) -->
            <div id="bulk-actions" class="hidden">
                <button type="button" onclick="confirmBulkDelete()" class="btn btn-error btn-sm btn-outline gap-2 font-bold shadow-sm">
                    <x-heroicon-o-trash class="w-4 h-4" />
                    Delete Selected (<span id="selected-count">0</span>)
                </button>
            </div>

            <form action="{{ route('admin.tickets.index') }}" method="GET" class="flex items-center gap-2">
                <div class="join border border-gray-300 rounded-xl overflow-hidden shadow-sm">
                    <input type="text" name="ticket_id" value="{{ request('ticket_id') }}" placeholder="Search ID..." class="input input-sm join-item w-28 focus:outline-none" />
                    <select name="status" class="select select-sm join-item focus:outline-none bg-white">
                        <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All Status</option>
                        <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary join-item px-4">Search</button>
                </div>
                @if(request()->anyFilled(['ticket_id', 'status']))
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-ghost">Reset</a>
                @endif
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-6 shadow-sm border-none bg-green-50 text-green-800 py-3">
            <x-heroicon-o-check-circle class="w-5 h-5" />
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card bg-base-100 shadow-sm border border-gray-200 overflow-hidden rounded-2xl">
        <form id="bulk-delete-form" action="{{ route('admin.tickets.bulk-delete') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-400">
                            <th class="w-12 pl-6 py-4">
                                <!-- Select all removed for safety -->
                            </th>
                            <th class="font-black uppercase text-[10px] tracking-widest py-4 text-center">ID</th>
                            <th class="font-black uppercase text-[10px] tracking-widest py-4">Subject</th>
                            <th class="font-black uppercase text-[10px] tracking-widest py-4">Requester</th>
                            <th class="font-black uppercase text-[10px] tracking-widest py-4">Department</th>
                            <th class="font-black uppercase text-[10px] tracking-widest py-4">Status</th>
                            <th class="font-black uppercase text-[10px] tracking-widest py-4">Last Updated</th>
                            <th class="font-black uppercase text-[10px] tracking-widest py-4 pr-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="pl-6">
                                    <input type="checkbox" name="ticket_ids[]" value="{{ $ticket->id }}" class="checkbox checkbox-xs ticket-checkbox" />
                                </td>
                                <td class="font-bold text-gray-400 text-center cursor-pointer" onclick="window.location='{{ route('admin.tickets.show', $ticket->id) }}'">#{{ $ticket->id }}</td>
                                <td class="cursor-pointer" onclick="window.location='{{ route('admin.tickets.show', $ticket->id) }}'">
                                    <div class="font-bold text-gray-800 line-clamp-1 truncate max-w-xs">{{ $ticket->title }}</div>
                                    <div class="text-[10px] uppercase font-bold tracking-tighter {{ 
                                        $ticket->priority == 'urgent' ? 'text-error' : (
                                        $ticket->priority == 'high' ? 'text-warning' : (
                                        $ticket->priority == 'medium' ? 'text-info' : 'text-gray-400'))
                                    }}">
                                        {{ $ticket->priority }} Priority
                                    </div>
                                </td>
                                <td class="cursor-pointer" onclick="window.location='{{ route('admin.tickets.show', $ticket->id) }}'">
                                    <div class="text-sm font-bold text-gray-700">{{ $ticket->user->name }}</div>
                                </td>
                                <td class="cursor-pointer" onclick="window.location='{{ route('admin.tickets.show', $ticket->id) }}'">
                                    <span class="badge badge-ghost badge-sm font-medium px-2 py-0 border-gray-200">{{ $ticket->department->name ?? 'N/A' }}</span>
                                </td>
                                <td class="cursor-pointer" onclick="window.location='{{ route('admin.tickets.show', $ticket->id) }}'">
                                    @if($ticket->status == 'open')
                                        <span class="badge badge-error badge-sm font-bold">OPEN</span>
                                    @elseif($ticket->status == 'in_progress')
                                        <span class="badge badge-warning badge-sm font-bold">IN PROGRESS</span>
                                    @elseif($ticket->status == 'closed')
                                        <span class="badge badge-success badge-sm font-bold text-white">CLOSED</span>
                                    @endif
                                </td>
                                <td class="text-xs text-gray-500 cursor-pointer" onclick="window.location='{{ route('admin.tickets.show', $ticket->id) }}'">
                                    {{ $ticket->updated_at->diffForHumans() }}
                                </td>
                                <td class="pr-6 text-right">
                                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-ghost btn-xs text-primary font-bold">Edit</a>
                                        <button type="button" 
                                            onclick="if(confirm('Delete this ticket?')) { document.getElementById('delete-form-{{ $ticket->id }}').submit(); }"
                                            class="btn btn-ghost btn-xs text-error font-bold">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-gray-400 italic">No tickets found matching your criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @if($tickets->hasPages())
            <div class="p-4 bg-gray-50/30 border-t border-gray-100 mt-auto">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

    @foreach($tickets as $ticket)
        <form id="delete-form-{{ $ticket->id }}" action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    <!-- Bulk Delete Confirmation Modal -->
    <dialog id="bulk_delete_modal" class="modal">
        <div class="modal-box rounded-3xl p-8 border border-base-300 bg-base-100">
            <h3 class="font-black text-2xl mb-4 text-error">Warning</h3>
            <p class="mb-6 opacity-70">Are you sure you want to delete <span id="modal-delete-count" class="font-bold">0</span> tickets? This action cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button type="button" class="btn btn-ghost rounded-xl font-bold" onclick="bulk_delete_modal.close()">Cancel</button>
                <button type="button" onclick="document.getElementById('bulk-delete-form').submit()" class="btn btn-error rounded-xl text-white font-black px-8">Confirm Delete</button>
            </div>
        </div>
    </dialog>

    <script>
        const ticketCheckboxes = document.querySelectorAll('.ticket-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCountLabel = document.getElementById('selected-count');
        const modalDeleteCount = document.getElementById('modal-delete-count');

        function updateBulkActionsVisibility() {
            const checkedCount = document.querySelectorAll('.ticket-checkbox:checked').length;
            if (checkedCount > 0) {
                bulkActions.classList.remove('hidden');
                selectedCountLabel.textContent = checkedCount;
                modalDeleteCount.textContent = checkedCount;
            } else {
                bulkActions.classList.add('hidden');
            }
        }



        ticketCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActionsVisibility);
        });

        function confirmBulkDelete() {
            bulk_delete_modal.showModal();
        }
    </script>
@endsection
