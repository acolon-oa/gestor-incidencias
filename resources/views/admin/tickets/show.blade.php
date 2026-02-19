@extends('layouts.app')

@section('title', 'Ticket Details - #' . $ticket->id)

@section('content')
<div class="card bg-base-100 shadow p-6">
    <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="flex justify-between items-start border-b pb-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold">{{ $ticket->title }}</h1>
                <div class="flex gap-2 mt-2">
                    @if($ticket->status == 'open')
                        <span class="badge badge-error">Open</span>
                    @elseif($ticket->status == 'in_progress')
                        <span class="badge badge-warning">In Progress</span>
                    @elseif($ticket->status == 'closed')
                        <span class="badge badge-success">Closed</span>
                    @endif
                    <span class="badge badge-ghost uppercase">{{ $ticket->priority }}</span>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="flex gap-2">
                    <select name="status" class="select select-bordered select-sm w-36">
                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>

                    <select name="priority" class="select select-bordered select-sm w-28">
                        <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm px-6 font-bold">Save Changes</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <h2 class="font-bold text-lg mb-2">Description</h2>
                <div class="bg-gray-50 dark:bg-base-200 p-4 rounded-lg mb-6">
                    {{ $ticket->description }}
                </div>

                <h2 class="font-bold text-lg mb-4">Activity</h2>
                <div class="space-y-4 mb-8">
                    @foreach($ticket->comments as $comment)
                        <div class="chat {{ $comment->user_id === auth()->id() ? 'chat-end' : 'chat-start' }}">
                            <div class="chat-header">
                                {{ $comment->user->name }}
                                <time class="text-xs opacity-50">{{ $comment->created_at->diffForHumans() }}</time>
                            </div>
                            <div class="chat-bubble {{ $comment->user_id === auth()->id() ? 'chat-bubble-primary' : '' }}">
                                {{ $comment->content }}
                            </div>
                        </div>
                    @endforeach
                </div>
    </form> {{-- Close main update form here before comment form --}}

                <div class="border-t pt-6">
                    <form action="{{ route('comments.store', $ticket->id) }}" method="POST">
                        @csrf
                        <textarea name="content" class="textarea textarea-bordered w-full" placeholder="Add a comment..." required></textarea>
                        <button type="submit" class="btn btn-neutral btn-sm mt-2">Post Comment</button>
                    </form>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-base-200 p-4 rounded-lg h-fit">
                <h3 class="font-bold border-b pb-2 mb-4 text-gray-500 uppercase text-xs">Details</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="block text-gray-400 mb-1">Requester</label>
                        <p class="font-bold px-1">{{ $ticket->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-1">Target Department (Redirection)</label>
                        {{-- This select belongs to the main form because it's inside the tags --}}
                        <select name="department_id" class="select select-bordered select-sm w-full font-bold" form="ticket-update-form">
                            @foreach(\App\Models\Department::all() as $dept)
                                <option value="{{ $dept->id }}" {{ $ticket->department_id == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-1">Created At</label>
                        <p class="font-bold px-1">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-1">Assign Agent</label>
                        <select name="assigned_to_id" class="select select-bordered select-sm w-full" form="ticket-update-form">
                            <option value="">Unassigned</option>
                            @php
                                $deptUsers = \App\Models\User::where('department_id', $ticket->department_id)
                                    ->whereHas('roles', fn($q) => $q->where('name', 'admin'))
                                    ->get();
                            @endphp
                            @foreach($deptUsers as $agent)
                                <option value="{{ $agent->id }}" {{ $ticket->assigned_to_id == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] opacity-40 mt-1 italic leading-tight">Only showing agents from the current department.</p>
                    </div>
                </div>
            </div>
        </div>
</div>

{{-- This is a little trick to use same form for inputs outside the actual <form> tags --}}
<form id="ticket-update-form" action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST" style="display:none;">
    @csrf
    @method('PATCH')
</form>

<script>
    // Copy select values to the hidden form just before submission if needed, 
    // or just use the 'form' attribute which is cleaner.
    document.querySelector('form[action$="update"]').id = 'ticket-update-form-main';
    // Link sidebar selects to the main form
    document.querySelectorAll('select[name="department_id"], select[name="assigned_to_id"]').forEach(el => {
        el.setAttribute('form', 'ticket-update-form-main');
    });
</script>
@endsection
