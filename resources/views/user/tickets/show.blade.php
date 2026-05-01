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
            <div class="bg-base-100 border border-base-content/5 rounded-3xl p-6 shadow-sm">
                <h2 class="text-[10px] font-black text-base-content/30 uppercase tracking-[0.2em] mb-3">Description</h2>
                <p class="text-base-content/70 leading-relaxed whitespace-pre-line text-base font-medium">{{ $ticket->description }}</p>

                @if($ticket->attachments->where('comment_id', null)->count() > 0)
                    <div class="mt-6 pt-6 border-t border-base-content/5 flex flex-wrap gap-3">
                        @foreach($ticket->attachments->where('comment_id', null) as $attachment)
                            <a href="{{ route('attachments.download', $attachment->id) }}" class="flex items-center gap-3 px-4 py-2 bg-base-200/50 hover:bg-primary/5 text-base-content/60 hover:text-primary rounded-2xl transition-all text-xs font-bold border border-base-content/5 shadow-sm">
                                <x-heroicon-o-document-arrow-down class="w-5 h-5" />
                                <span>{{ $attachment->filename }}</span>
                                <span class="text-[10px] opacity-40">({{ number_format($attachment->size / 1024, 1) }} KB)</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Conversation --}}
            <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 shadow-sm h-[800px] flex flex-col">
                <h2 class="text-[10px] font-black text-base-content/30 uppercase tracking-[0.2em] mb-8 flex-none">Conversation History</h2>

                @if($ticket->comments->isEmpty())
                    <div class="flex-1 flex flex-col items-center justify-center opacity-30">
                        <x-heroicon-o-chat-bubble-left-right class="w-12 h-12 mb-3" />
                        <p class="text-sm italic font-bold">No messages yet.</p>
                    </div>
                @else
                    <div class="flex-1 space-y-6 overflow-y-auto pr-4 scrollbar-thin scrollbar-thumb-base-content/10 scrollbar-track-transparent">
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

                                        @if($comment->attachments->count() > 0)
                                            <div class="mt-4 flex flex-wrap gap-2">
                                                @foreach($comment->attachments as $attachment)
                                                    <a href="{{ route('attachments.download', $attachment->id) }}" 
                                                       class="flex items-center gap-2 px-3 py-2 rounded-xl {{ $comment->user_id === auth()->id() ? 'bg-white/10 hover:bg-white/20 text-white' : 'bg-base-300 hover:bg-base-content/10 text-base-content/60' }} transition-colors text-[10px] font-bold border border-transparent {{ $comment->user_id === auth()->id() ? '' : 'border-base-content/5' }}">
                                                        <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                                                        <span class="truncate max-w-[150px]">{{ $attachment->filename }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
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
                        <div class="flex flex-col gap-4 mt-4">
                            <div class="flex items-center gap-2">
                                <label class="btn btn-ghost btn-xs text-base-content/40 hover:text-primary lowercase font-normal gap-2">
                                    <x-heroicon-o-paper-clip class="w-4 h-4" />
                                    <span>Attach images or logs</span>
                                    <input type="file" name="attachments[]" form="comment-form-user" class="hidden" multiple onchange="handleFileSelect(this, 'preview-user')" />
                                </label>

                                @if(false) {{-- Moved to description section --}}
                                @endif
                            </div>
                            <div id="preview-user" class="flex flex-wrap gap-2 mb-2"></div>
                            <div class="flex justify-end">
                                <button type="submit" form="comment-form-user" class="btn btn-primary px-10 font-black shadow-lg shadow-primary/20">Send Response</button>
                            </div>
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
        <div class="space-y-8 sticky top-8 self-start">
            <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 space-y-8 shadow-sm">
                <h3 class="text-[10px] font-black text-base-content/30 uppercase tracking-[0.2em] border-b border-base-content/5 pb-5">Management Panel</h3>

                <form action="{{ route('user.tickets.update', $ticket->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="w-full">
                        <div class="mb-3"><span class="font-black text-base-content/40 text-[10px] uppercase tracking-widest">Global Status</span></div>
                        <select name="status" class="select select-bordered bg-base-100 border-base-content/10 rounded-2xl font-bold w-full text-xs">
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>

                    <div class="w-full">
                        <div class="mb-3"><span class="font-black text-base-content/40 text-[10px] uppercase tracking-widest">Assign to Agent</span></div>
                        <select name="assigned_to_id" class="select select-bordered bg-base-100 border-base-content/10 rounded-2xl font-bold w-full text-xs">
                            <option value="">Unassigned</option>
                            @php
                                $agents = \App\Models\User::role('admin')->get();
                            @endphp
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ $ticket->assigned_to_id == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-full font-black rounded-2xl shadow-xl shadow-primary/20 py-4 h-auto">Sync Changes</button>
                </form>

                <div class="pt-6 border-t border-base-content/5 mt-4">
                    <h4 class="text-[10px] font-black text-base-content/20 uppercase tracking-[0.2em] mb-4">Incident Info</h4>
                    <div class="space-y-4 text-xs">
                        <div class="flex justify-between items-center font-bold">
                            <span class="text-base-content/40">Requester</span>
                            <span class="text-base-content/80">{{ $ticket->user->name }}</span>
                        </div>
                        <div class="flex justify-between items-center font-bold">
                            <span class="text-base-content/40">Department</span>
                            <span class="text-base-content/80">{{ $ticket->department->name ?? 'General' }}</span>
                        </div>
                        <div class="flex justify-between items-center font-bold">
                            <span class="text-base-content/40">Created</span>
                            <span class="text-base-content/80">{{ $ticket->created_at->timezone('Europe/Madrid')->format('M d, Y H:i') }}</span>
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
    <form id="comment-form-user" action="{{ route('comments.store', $ticket->id) }}" method="POST" class="hidden" enctype="multipart/form-data">
        @csrf
    </form>

    <script>
        function handleFileSelect(input, previewId) {
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';
            
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center gap-2 px-3 py-1.5 bg-primary/5 text-primary rounded-xl text-[10px] font-black uppercase tracking-wider border border-primary/10';
                    div.innerHTML = `
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13" /></svg>
                        <span>${file.name}</span>
                    `;
                    preview.appendChild(div);
                });
            }
        }
    </script>
</div>
@endsection
