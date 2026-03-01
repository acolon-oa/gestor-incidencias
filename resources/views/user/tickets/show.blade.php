@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id . ' — ' . $ticket->title)

@section('content')
<div class="w-full max-w-screen-2xl mx-auto py-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <nav class="flex text-xs font-semibold uppercase tracking-wider text-base-content/40 mb-2 gap-2">
                <a href="{{ route('user.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-base-content font-bold">#{{ $ticket->id }}</span>
            </nav>
            <h1 class="text-4xl font-extrabold text-base-content tracking-tight">{{ $ticket->title }}</h1>
            <div class="flex flex-wrap items-center gap-3 mt-4">
                @if($ticket->status == 'open')
                    <span class="badge badge-error badge-sm font-black text-[10px] tracking-widest p-3">OPEN</span>
                @elseif($ticket->status == 'in_progress')
                    <span class="badge badge-warning badge-sm font-black text-[10px] tracking-widest p-3">IN PROGRESS</span>
                @elseif($ticket->status == 'closed')
                    <span class="badge badge-success badge-sm font-black text-[10px] tracking-widest p-3 text-white">RESOLVED</span>
                @endif
                
                @php
                    $prioritySema = [
                        'urgent' => 'bg-error/10 text-error border-error/20',
                        'high'   => 'bg-warning/10 text-warning border-warning/20',
                        'medium' => 'bg-info/10 text-info border-info/20',
                        'low'    => 'bg-base-content/5 text-base-content/40 border-base-content/10',
                    ];
                    $ps = $prioritySema[$ticket->priority] ?? 'bg-base-content/5 text-base-content/40 border-base-content/10';
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase border {{ $ps }}">{{ $ticket->priority }} PRIORITY</span>
                
                <span class="text-sm text-base-content/40 ml-2 font-medium italic">Submitted {{ $ticket->created_at->diffForHumans() }}</span>
            </div>
        </div>
        <a href="{{ route('user.dashboard') }}" class="btn btn-ghost btn-sm font-black text-xs uppercase tracking-widest opacity-40 hover:opacity-100 transition-opacity">← Back to Dashboard</a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-10">

        {{-- Main content column --}}
        <div class="xl:col-span-2 space-y-8">

            {{-- Description --}}
            <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 shadow-sm">
                <h2 class="text-[10px] font-black text-base-content/30 uppercase tracking-[0.2em] mb-6">Initial Report</h2>
                <p class="text-base-content/80 leading-relaxed whitespace-pre-line text-lg font-medium">{{ $ticket->description }}</p>
            </div>

            {{-- Conversation --}}
            <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 shadow-sm">
                <h2 class="text-[10px] font-black text-base-content/30 uppercase tracking-[0.2em] mb-8">Conversation History</h2>

                @if($ticket->comments->isEmpty())
                    <div class="text-center py-10 opacity-30">
                        <x-heroicon-o-chat-bubble-left-right class="w-12 h-12 mx-auto mb-3" />
                        <p class="text-sm italic font-bold">No messages yet.</p>
                    </div>
                @else
                    <div class="space-y-6 mb-10">
                        @foreach($ticket->comments as $comment)
                            <div class="flex {{ $comment->user_id === auth()->id() ? 'flex-row-reverse' : 'flex-row' }} gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black text-sm flex-shrink-0">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <div class="max-w-xl {{ $comment->user_id === auth()->id() ? 'items-end' : 'items-start' }} flex flex-col gap-1.5">
                                    <div class="flex gap-2 items-center {{ $comment->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                        <span class="text-xs font-black text-base-content/60">{{ $comment->user->name }}</span>
                                        <span class="text-[10px] text-base-content/20 font-bold italic">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="px-5 py-4 rounded-3xl text-sm leading-relaxed {{ $comment->user_id === auth()->id() ? 'bg-primary text-white rounded-tr-sm shadow-md shadow-primary/10' : 'bg-base-200 text-base-content/90 rounded-tl-sm border border-base-content/5 shadow-sm' }}">
                                        {{ $comment->content }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($ticket->status !== 'closed')
                    {{-- Inner Response form --}}
                    <div class="pt-8 border-t border-base-content/5 mt-4">
                        <textarea name="content" form="comment-form-user"
                            class="textarea w-full bg-base-200/50 border-base-content/5 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all rounded-2xl p-6 text-sm leading-relaxed placeholder-base-content/20 min-h-[140px]"
                            placeholder="Add more information or ask a question..."
                            required></textarea>
                        <div class="flex justify-end mt-4">
                            <button type="submit" form="comment-form-user" class="btn btn-primary px-10 font-black shadow-lg shadow-primary/20">Send Response</button>
                        </div>
                    </div>
                @else
                    <div class="bg-base-200/30 rounded-3xl p-8 text-center border border-dashed border-base-content/10 mt-4">
                        <p class="text-sm font-bold text-base-content/30 italic">Incident resolved. Conversation is closed.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Info sidebar --}}
        <div class="space-y-8">
            <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 space-y-8 sticky top-8 shadow-sm">
                <h3 class="text-[10px] font-black text-base-content/30 uppercase tracking-[0.2em] border-b border-base-content/5 pb-5">Incident Info</h3>

                <div>
                    <p class="text-base-content/40 text-[10px] font-black uppercase tracking-widest mb-2">Assigned Agent</p>
                    @if($ticket->assignedTo)
                        <div class="flex items-center gap-3 bg-base-200/50 p-3 rounded-2xl">
                             <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-black text-xs">
                                {{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}
                             </div>
                             <span class="font-bold text-base-content text-sm">{{ $ticket->assignedTo->name }}</span>
                        </div>
                    @else
                        <p class="text-sm text-base-content/40 italic font-bold">Waiting for assignment...</p>
                    @endif
                </div>

                <div>
                    <p class="text-base-content/40 text-[10px] font-black uppercase tracking-widest mb-2">Department</p>
                    <p class="font-bold text-base-content">{{ $ticket->department->name ?? 'General' }}</p>
                </div>

                <div class="pt-6 border-t border-base-content/5 mt-4">
                    <h4 class="text-[10px] font-black text-base-content/20 uppercase tracking-[0.2em] mb-4">Timestamps</h4>
                    <div class="space-y-4 text-xs">
                        <div class="flex justify-between items-center font-bold">
                            <span class="text-base-content/40">Created</span>
                            <span class="text-base-content/80">{{ $ticket->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center font-bold">
                            <span class="text-base-content/40">Updated</span>
                            <span class="text-base-content/80">{{ $ticket->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between items-center font-bold">
                            <span class="text-base-content/40">ID</span>
                            <span class="text-base-content/20">#{{ $ticket->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Separate comment form --}}
    <form id="comment-form-user" action="{{ route('comments.store', $ticket->id) }}" method="POST" class="hidden">
        @csrf
    </form>
</div>
@endsection
