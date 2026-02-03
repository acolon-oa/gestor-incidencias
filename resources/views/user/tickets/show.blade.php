@extends('layouts.app')

@section('title', 'Ticket Details')

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
                    <span class="badge badge-success">Resolved</span>
                @endif
            </div>
        </div>
        <a href="{{ route('user.dashboard') }}" class="btn btn-ghost btn-sm">Back</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <h2 class="font-bold text-lg mb-2">My Description</h2>
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                {{ $ticket->description }}
            </div>

            <h2 class="font-bold text-lg mb-4">Conversation</h2>
            <div class="space-y-4 mb-6">
                @foreach($ticket->comments as $comment)
                    <div class="chat {{ $comment->user_id === auth()->id() ? 'chat-end' : 'chat-start' }}">
                        <div class="chat-header">
                            {{ $comment->user->name }}
                        </div>
                        <div class="chat-bubble {{ $comment->user_id === auth()->id() ? 'chat-bubble-primary' : '' }}">
                            {{ $comment->content }}
                        </div>
                    </div>
                @endforeach
            </div>

            @if($ticket->status !== 'closed')
            <form action="{{ route('comments.store', $ticket->id) }}" method="POST">
                @csrf
                <textarea name="content" class="textarea textarea-bordered w-full" placeholder="Add more info..." required></textarea>
                <button type="submit" class="btn btn-primary mt-2">Send</button>
            </form>
            @endif
        </div>

        <div class="bg-gray-50 p-4 rounded-lg h-fit text-sm">
            <h3 class="font-bold border-b pb-2 mb-4 text-gray-500 uppercase text-xs">Ticket Info</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-400">Department</label>
                    <p class="font-bold">{{ $ticket->department->name }}</p>
                </div>
                <div>
                    <label class="block text-gray-400">Priority</label>
                    <p class="font-bold uppercase">{{ $ticket->priority }}</p>
                </div>
                <div>
                    <label class="block text-gray-400">Assigned Agent</label>
                    <p class="font-bold">{{ $ticket->assignedTo->name ?? 'None' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
