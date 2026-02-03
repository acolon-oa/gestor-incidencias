@extends('layouts.app')

@section('title', 'Ticket Details - #' . $ticket->id)

@section('content')
<div class="card bg-base-100 shadow p-6">
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
        <div class="flex gap-2">
            <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
                @csrf
                @method('PATCH')
                @if($ticket->status !== 'in_progress' && $ticket->status !== 'closed')
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" class="btn btn-warning btn-sm">Start Progress</button>
                @elseif($ticket->status === 'in_progress')
                    <input type="hidden" name="status" value="closed">
                    <button type="submit" class="btn btn-success btn-sm text-white">Close Ticket</button>
                @elseif($ticket->status === 'closed')
                    <input type="hidden" name="status" value="open">
                    <button type="submit" class="btn btn-outline btn-sm">Re-open</button>
                @endif
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <h2 class="font-bold text-lg mb-2">Description</h2>
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                {{ $ticket->description }}
            </div>

            <h2 class="font-bold text-lg mb-4">Activity</h2>
            <div class="space-y-4 mb-6">
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

            <form action="{{ route('comments.store', $ticket->id) }}" method="POST" class="mt-4">
                @csrf
                <textarea name="content" class="textarea textarea-bordered w-full" placeholder="Add a comment..." required></textarea>
                <button type="submit" class="btn btn-primary mt-2">Post Comment</button>
            </form>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg h-fit">
            <h3 class="font-bold border-b pb-2 mb-4 text-gray-500 uppercase text-xs">Details</h3>
            <div class="space-y-4 text-sm">
                <div>
                    <label class="block text-gray-400">Requester</label>
                    <p class="font-bold">{{ $ticket->user->name }}</p>
                </div>
                <div>
                    <label class="block text-gray-400">Department</label>
                    <p class="font-bold">{{ $ticket->department->name }}</p>
                </div>
                <div>
                    <label class="block text-gray-400">Created At</label>
                    <p class="font-bold">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-gray-400">Assigned To</label>
                    <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="assigned_to_id" class="select select-bordered select-sm w-full mt-1" onchange="this.form.submit()">
                            <option value="">Unassigned</option>
                            @foreach(\App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->get() as $admin)
                                <option value="{{ $admin->id }}" {{ $ticket->assigned_to_id == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
