@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id . ' — ' . $ticket->title)

@section('content')
<div class="w-full max-w-screen-2xl mx-auto py-8">

    @if(session('success'))
        <div class="alert alert-success mb-8 rounded-2xl border-none shadow-sm bg-green-500/10 text-green-500 py-4">
            <x-heroicon-o-check-circle class="w-6 h-6" />
            <span class="font-bold tracking-tight">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <nav class="flex text-xs font-semibold uppercase tracking-wider text-base-content/40 mb-2 gap-2">
                <a href="{{ route('admin.tickets.index') }}" class="hover:text-primary transition-colors">Tickets</a>
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
                    <span class="badge badge-success badge-sm font-black text-[10px] tracking-widest p-3 text-white">CLOSED</span>
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
                
                <span class="text-sm text-base-content/40 ml-2 font-medium italic">Reported by <span class="text-base-content/80 font-bold not-italic">{{ $ticket->user->name }}</span> · {{ $ticket->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    {{-- Unified update form --}}
    <form id="ticket-update-form" action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-10">

            {{-- Main content --}}
            <div class="xl:col-span-2 space-y-8">

                {{-- Description --}}
                <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 shadow-sm">
                    <h2 class="text-[10px] font-black text-base-content/30 uppercase tracking-[0.2em] mb-6">Detailed Description</h2>
                    <p class="text-base-content/80 leading-relaxed whitespace-pre-line text-lg font-medium">{{ $ticket->description }}</p>
                </div>

                {{-- Activity / Comments --}}
                <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 shadow-sm">
                    <h2 class="text-[10px] font-black text-base-content/30 uppercase tracking-[0.2em] mb-8">Activity & History</h2>

                    @if($ticket->comments->isEmpty())
                        <div class="text-center py-10 opacity-30">
                            <x-heroicon-o-chat-bubble-left-right class="w-12 h-12 mx-auto mb-3" />
                            <p class="text-sm italic font-bold">No discussions yet. Use the form below to start.</p>
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
                    
                    {{-- Inner Comment form (Integrated) --}}
                    <div class="pt-8 border-t border-base-content/5 mt-4">
                        <textarea name="content" form="comment-form-inner"
                            class="textarea w-full bg-base-200/50 border-base-content/5 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all rounded-2xl p-6 text-sm leading-relaxed placeholder-base-content/20 min-h-[140px]"
                            placeholder="Type your response or internal note here..."
                            required></textarea>
                        <div class="flex justify-end mt-4">
                            <button type="submit" form="comment-form-inner" class="btn btn-primary px-10 font-black shadow-lg shadow-primary/20">Post Update</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar panel --}}
            <div class="space-y-8">
                {{-- Quick actions card --}}
                <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 space-y-8 sticky top-8 shadow-sm">
                    <h3 class="text-[10px] font-black text-base-content/30 uppercase tracking-[0.2em] border-b border-base-content/5 pb-5">Management Panel</h3>

                    <div class="w-full">
                        <div class="mb-3"><span class="font-black text-base-content/40 text-[10px] uppercase tracking-widest">Global Status</span></div>
                        <select name="status" class="select select-bordered bg-base-200/50 border-base-content/10 rounded-2xl font-bold w-full text-xs">
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="w-full">
                        <div class="mb-3"><span class="font-black text-base-content/40 text-[10px] uppercase tracking-widest">Priority level</span></div>
                        <select name="priority" class="select select-bordered bg-base-200/50 border-base-content/10 rounded-2xl font-bold w-full text-xs">
                            <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <div class="w-full">
                        <div class="mb-3"><span class="font-black text-base-content/40 text-[10px] uppercase tracking-widest">Transfer to Dep.</span></div>
                        <select name="department_id" class="select select-bordered bg-base-200/50 border-base-content/10 rounded-2xl font-bold w-full text-xs">
                            @foreach(\App\Models\Department::all() as $dept)
                                <option value="{{ $dept->id }}" {{ $ticket->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full">
                        <div class="mb-3"><span class="font-black text-base-content/40 text-[10px] uppercase tracking-widest">Responsible Agent</span></div>
                        <select name="assigned_to_id" class="select select-bordered bg-base-200/50 border-base-content/10 rounded-2xl font-bold w-full text-xs">
                            <option value="">Unassigned</option>
                            @php
                                $deptUsers = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->get();
                            @endphp
                            @foreach($deptUsers as $agent)
                                <option value="{{ $agent->id }}" {{ $ticket->assigned_to_id == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-full font-black rounded-2xl shadow-xl shadow-primary/20 py-4 h-auto">Sync Changes</button>
                    
                    <div class="pt-6 border-t border-base-content/5 mt-4">
                        <h4 class="text-[10px] font-black text-base-content/20 uppercase tracking-[0.2em] mb-4">Metadata</h4>
                        <div class="space-y-4 text-xs">
                            <div class="flex justify-between items-center font-bold">
                                <span class="text-base-content/40">Created at</span>
                                <span class="text-base-content/80">{{ $ticket->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center font-bold">
                                <span class="text-base-content/40">Last activity</span>
                                <span class="text-base-content/80">{{ $ticket->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex justify-between items-center font-bold">
                                <span class="text-base-content/40">Internal ID</span>
                                <span class="text-base-content/20">#{{ $ticket->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Separate comment form to avoid nested forms issues --}}
    <form id="comment-form-inner" action="{{ route('comments.store', $ticket->id) }}" method="POST" class="hidden">
        @csrf
    </form>
</div>
@endsection
