@extends('layouts.app')

@section('title', 'Agile Board')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-base-content tracking-tight">Agile Board</h1>
            <p class="text-sm text-base-content/40 mt-1 font-medium italic">Drag and drop tickets to update their status.</p>
        </div>
    </div>

    <!-- Kanban Board Container -->
    <div class="flex flex-col md:flex-row gap-6 overflow-x-auto pb-6 h-full items-start">
        
        <!-- OPEN Column -->
        <div class="flex-1 min-w-[300px] bg-base-200/50 rounded-3xl p-4 flex flex-col gap-4 border border-base-content/5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-black text-sm uppercase tracking-widest flex items-center gap-2 text-error">
                    <span class="w-2 h-2 rounded-full bg-error"></span>
                    Open
                </h3>
                <span class="badge badge-sm badge-ghost font-bold opacity-60">{{ count($kanban['open']) }}</span>
            </div>
            <div id="column-open" data-status="open" class="kanban-column flex flex-col gap-3 min-h-[150px]">
                @foreach($kanban['open'] as $ticket)
                    @include('admin.tickets.partials.kanban-card', ['ticket' => $ticket])
                @endforeach
            </div>
        </div>

        <!-- IN PROGRESS Column -->
        <div class="flex-1 min-w-[300px] bg-base-200/50 rounded-3xl p-4 flex flex-col gap-4 border border-base-content/5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-black text-sm uppercase tracking-widest flex items-center gap-2 text-warning">
                    <span class="w-2 h-2 rounded-full bg-warning"></span>
                    In Progress
                </h3>
                <span class="badge badge-sm badge-ghost font-bold opacity-60">{{ count($kanban['in_progress']) }}</span>
            </div>
            <div id="column-in-progress" data-status="in_progress" class="kanban-column flex flex-col gap-3 min-h-[150px]">
                @foreach($kanban['in_progress'] as $ticket)
                    @include('admin.tickets.partials.kanban-card', ['ticket' => $ticket])
                @endforeach
            </div>
        </div>

        <!-- CLOSED Column -->
        <div class="flex-1 min-w-[300px] bg-base-200/50 rounded-3xl p-4 flex flex-col gap-4 border border-base-content/5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-black text-sm uppercase tracking-widest flex items-center gap-2 text-success">
                    <span class="w-2 h-2 rounded-full bg-success"></span>
                    Closed
                </h3>
                <span class="badge badge-sm badge-ghost font-bold opacity-60">{{ count($kanban['closed']) }}</span>
            </div>
            <div id="column-closed" data-status="closed" class="kanban-column flex flex-col gap-3 min-h-[150px]">
                @foreach($kanban['closed'] as $ticket)
                    @include('admin.tickets.partials.kanban-card', ['ticket' => $ticket])
                @endforeach
            </div>
        </div>

    </div>

    <!-- Include SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const columns = document.querySelectorAll('.kanban-column');
            
            columns.forEach(column => {
                new Sortable(column, {
                    group: 'kanban', // set both lists to same group
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: function (evt) {
                        const itemEl = evt.item;  // dragged HTMLElement
                        const toColumn = evt.to;  // target list
                        const ticketId = itemEl.getAttribute('data-id');
                        const newStatus = toColumn.getAttribute('data-status');

                        if(evt.from === toColumn) return; // Didn't change column

                        // Show some loading indicator if you want, or just let it update
                        itemEl.style.opacity = '0.5';

                        fetch(`/admin/tickets/${ticketId}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ status: newStatus })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                itemEl.style.opacity = '1';
                                // Optionally update the counters
                            } else {
                                alert('Error updating status');
                                evt.from.appendChild(itemEl); // Revert
                                itemEl.style.opacity = '1';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred');
                            evt.from.appendChild(itemEl); // Revert
                            itemEl.style.opacity = '1';
                        });
                    },
                });
            });
        });
    </script>
@endsection
