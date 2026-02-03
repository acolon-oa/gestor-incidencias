@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="h-[calc(100vh-120px)] flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-300 shrink-0">
        <div class="flex items-center gap-6">
            <h1 class="text-3xl font-black text-gray-900">#{{ $ticket->id }}</h1>
            <span class="text-gray-300 text-2xl">|</span>
            <h2 class="text-xl font-bold text-gray-700">{{ $ticket->title }}</h2>
        </div>
        <div class="flex items-center gap-4">
             @if($ticket->status == 'open')
                <span class="badge badge-error badge-lg py-4 px-6 font-black tracking-widest text-xs">OPEN</span>
            @elseif($ticket->status == 'in_progress')
                <span class="badge badge-warning badge-lg py-4 px-6 font-black tracking-widest text-xs">IN PROGRESS</span>
            @elseif($ticket->status == 'closed')
                <span class="badge badge-success badge-lg py-4 px-6 font-black tracking-widest text-xs text-white">CLOSED</span>
            @endif

            @if($ticket->priority == 'low')
                <span class="badge badge-lg border-gray-300 bg-gray-100 text-gray-600 font-bold uppercase text-[11px] py-4 px-6">Low Priority</span>
            @elseif($ticket->priority == 'medium')
                <span class="badge badge-lg badge-info font-bold uppercase text-[11px] py-4 px-6">Medium Priority</span>
            @elseif($ticket->priority == 'high')
                <span class="badge badge-lg badge-warning font-bold uppercase text-[11px] py-4 px-6">High Priority</span>
            @elseif($ticket->priority == 'urgent')
                <span class="badge badge-lg badge-error font-bold uppercase text-[11px] py-4 px-6 animate-pulse">Urgent Priority</span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4 py-3 shadow-sm border-none bg-green-50 text-green-800 rounded-xl shrink-0">
            <x-heroicon-o-check-circle class="w-5 h-5" />
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex-1 overflow-hidden grid grid-cols-1 lg:grid-cols-3 gap-10 min-h-0">
        
        <!-- Left Column: Content (2/3 width) -->
        <div class="lg:col-span-2 flex flex-col gap-6 h-full min-h-0">
            <!-- Description section (Fixed height or scrollable if very long) -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm shrink-0">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Description</h3>
                <div class="text-gray-800 leading-relaxed whitespace-pre-wrap text-base italic max-h-32 overflow-y-auto">
                    {{ $ticket->description }}
                </div>
            </div>

            <!-- Messages section (Main scrollable area) -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex-1 flex flex-col min-h-0">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 border-b pb-2 shrink-0">Activity Feed</h3>
                <div class="flex-1 overflow-y-auto space-y-6 pr-2 mb-4 scrollbar-thin scrollbar-thumb-gray-200 scrollbar-track-transparent">
                    @forelse($ticket->comments as $comment)
                        <div class="chat {{ $comment->user_id === auth()->id() ? 'chat-end' : 'chat-start' }}">
                            <div class="chat-header text-[10px] mb-1 opacity-60 font-bold">
                                {{ $comment->user->name }}
                                <time class="ml-2 font-normal">{{ $comment->created_at->format('M d, H:i') }}</time>
                            </div>
                            <div class="chat-bubble {{ $comment->user_id === auth()->id() ? 'chat-bubble-primary' : 'bg-gray-100 text-gray-800' }} text-sm p-4 shadow-sm rounded-2xl">
                                {{ $comment->content }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400 italic text-base">No activity yet recorded.</div>
                    @endforelse
                </div>

                <div class="pt-4 border-t border-gray-100 shrink-0">
                    <form action="{{ route('comments.store', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="flex gap-4">
                            <textarea name="content" class="textarea textarea-bordered flex-1 focus:ring-4 focus:ring-primary/10 min-h-[80px] transition-all text-base p-4 rounded-xl resize-none" placeholder="Write a response..." required></textarea>
                            <button type="submit" class="btn btn-primary h-auto px-8 shadow-xl shadow-primary/20 rounded-xl font-bold">Reply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar (1/3 width) -->
        <div class="flex flex-col h-full min-h-0">
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm flex flex-col h-full overflow-y-auto">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 border-b border-gray-200 pb-3 shrink-0">Management Center</h3>
                
                <form id="update-ticket-form" action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <div class="form-control">
                        <label class="label pt-0 mb-1"><span class="label-text text-[10px] font-black text-gray-500 uppercase tracking-widest">Update Status</span></label>
                        <select name="status" class="select select-bordered select-md w-full font-bold h-12 text-sm rounded-xl">
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label pt-0 mb-1"><span class="label-text text-[10px] font-black text-gray-500 uppercase tracking-widest">Set Priority</span></label>
                        <select name="priority" class="select select-bordered select-md w-full font-bold h-12 text-sm rounded-xl">
                            <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label pt-0 mb-1"><span class="label-text text-[10px] font-black text-gray-500 uppercase tracking-widest">Assign Technician</span></label>
                        <select name="assigned_to_id" class="select select-bordered select-md w-full font-bold h-12 text-sm rounded-xl">
                            <option value="">Unassigned</option>
                            @foreach(\App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->get() as $admin)
                                <option value="{{ $admin->id }}" {{ $ticket->assigned_to_id == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="mt-auto pt-6 border-t border-gray-200 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest opacity-70">Requester</span>
                            <span class="text-xs font-bold text-gray-800">{{ $ticket->user->name }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest opacity-70">Dept</span>
                            <span class="text-xs font-bold text-gray-800">{{ $ticket->department->name }}</span>
                        </div>
                    </div>

                    <div class="pt-6 space-y-3">
                        <button type="submit" form="update-ticket-form" class="btn btn-primary btn-md btn-block shadow-lg shadow-primary/20 rounded-xl font-black text-xs">
                            SAVE CHANGES
                        </button>
                        
                        <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Permanently delete?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-xs btn-block text-error font-black hover:bg-red-50 rounded-xl uppercase tracking-tighter">
                                Remove Ticket
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
