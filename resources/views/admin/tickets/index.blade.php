@extends('layouts.app')

@section('title', 'Ticket Repository')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-base-content tracking-tight">Ticket Repository</h1>
            <p class="text-sm text-base-content/40 mt-1 font-medium italic">Manage and supervise all active incidents in the organization.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-4">
            <!-- Bulk Actions -->
            <div id="bulk-actions" class="hidden">
                <button type="button" onclick="confirmBulkDelete()" class="btn btn-error btn-sm btn-outline gap-2 font-bold shadow-sm rounded-xl">
                    <x-heroicon-o-trash class="w-4 h-4" />
                    Delete (<span id="selected-count">0</span>)
                </button>
            </div>

            <form action="{{ route('admin.tickets.index') }}" method="GET" class="flex items-center gap-2">
                <div class="join border border-base-content/10 rounded-2xl overflow-hidden shadow-sm bg-base-100">
                    <input type="text" name="ticket_id" value="{{ request('ticket_id') }}" placeholder="Search ID..." class="input input-sm join-item w-28 bg-transparent focus:outline-none text-xs font-bold" />
                    <select name="status" class="select select-sm join-item bg-transparent focus:outline-none text-xs font-bold border-l border-base-content/10">
                        <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All Status</option>
                        <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary join-item px-6 font-bold">Search</button>
                </div>
                @if(request()->anyFilled(['ticket_id', 'status']))
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-ghost btn-circle" title="Reset filters">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </a>
                @endif
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-8 rounded-2xl border-none shadow-sm bg-green-500/10 text-green-500 py-4">
            <x-heroicon-o-check-circle class="w-6 h-6" />
            <span class="font-bold tracking-tight">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-base-100 shadow-sm border border-base-content/5 overflow-hidden rounded-3xl">
        <form id="bulk-delete-form" action="{{ route('admin.tickets.bulk-delete') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="overflow-x-auto">
                <table class="table w-full table-zebra-zebra">
                    <thead>
                        <tr class="bg-base-200/50 text-base-content/40 border-b border-base-content/5">
                            <th class="w-12 pl-8 py-5"></th>
                            <th class="font-black uppercase text-[10px] tracking-widest text-center">ID</th>
                            <th class="font-black uppercase text-[10px] tracking-widest">Incident Details</th>
                            <th class="font-black uppercase text-[10px] tracking-widest">Requester</th>
                            <th class="font-black uppercase text-[10px] tracking-widest text-center">Department</th>
                            <th class="font-black uppercase text-[10px] tracking-widest text-center">Status</th>
                            <th class="font-black uppercase text-[10px] tracking-widest text-right pr-8">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base-content/5 font-medium">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-primary/5 transition-colors group cursor-pointer" onclick="handleRowClick(event, '{{ route('admin.tickets.show', $ticket->id) }}')">
                                <td class="pl-8">
                                    <input type="checkbox" name="ticket_ids[]" value="{{ $ticket->id }}" class="checkbox checkbox-sm checkbox-primary ticket-checkbox" onclick="event.stopPropagation()" />
                                </td>
                                <td class="text-center font-black text-base-content/20">#{{ $ticket->id }}</td>
                                <td>
                                    <div class="font-bold text-base-content text-sm group-hover:text-primary transition-colors">{{ $ticket->title }}</div>
                                    <div class="text-[10px] uppercase font-black mt-0.5 tracking-widest {{ 
                                        $ticket->priority == 'urgent' ? 'text-error' : (
                                        $ticket->priority == 'high' ? 'text-warning' : (
                                        $ticket->priority == 'medium' ? 'text-info' : 'text-base-content/30'))
                                    }}">
                                        {{ $ticket->priority }} Priority
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-base-200 flex items-center justify-center text-[10px] font-black">
                                            {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm text-base-content">{{ $ticket->user->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-ghost badge-sm font-bold opacity-60 border-base-content/10">{{ $ticket->department->name ?? 'N/A' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($ticket->status == 'open')
                                        <span class="badge badge-error badge-sm font-black text-[10px]">OPEN</span>
                                    @elseif($ticket->status == 'in_progress')
                                        <span class="badge badge-warning badge-sm font-black text-[10px]">IN PROGRESS</span>
                                    @elseif($ticket->status == 'closed')
                                        <span class="badge badge-success badge-sm font-black text-[10px] text-white">CLOSED</span>
                                    @endif
                                </td>
                                <td class="pr-8 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-ghost btn-xs text-primary font-black px-4 rounded-lg hover:bg-primary/10" onclick="event.stopPropagation()">VIEW</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-20">
                                    <div class="w-16 h-16 bg-base-200 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                        <x-heroicon-o-inbox class="w-8 h-8 text-base-content/20" />
                                    </div>
                                    <p class="text-base-content/40 font-bold italic">No incident reports found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @if($tickets->hasPages())
            <div class="p-6 bg-base-200/30 border-t border-base-content/5">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

    <!-- Modals & Scripts -->
    <dialog id="bulk_delete_modal" class="modal">
        <div class="modal-box rounded-3xl p-8 border border-base-content/5 bg-base-100 shadow-2xl">
            <div class="w-12 h-12 bg-error/10 text-error rounded-2xl flex items-center justify-center mb-6">
                <x-heroicon-o-trash class="w-6 h-6" />
            </div>
            <h3 class="font-black text-2xl mb-2 text-base-content">Confirm Deletion</h3>
            <p class="mb-8 text-base-content/60 leading-relaxed font-medium">You are about to delete <span id="modal-delete-count" class="font-black text-error">0</span> tickets. This action is irreversible.</p>
            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <button type="button" class="btn btn-ghost font-bold rounded-xl px-8" onclick="bulk_delete_modal.close()">Cancel</button>
                <button type="button" onclick="document.getElementById('bulk-delete-form').submit()" class="btn btn-error text-white font-black px-10 rounded-xl shadow-lg shadow-error/20">Delete Everything</button>
            </div>
        </div>
    </dialog>

    <script>
        function handleRowClick(event, url) {
            if (event.target.type !== 'checkbox' && !event.target.classList.contains('checkbox')) {
                window.location = url;
            }
        }

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
