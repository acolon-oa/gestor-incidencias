<div data-id="{{ $ticket->id }}" class="bg-base-100 p-4 rounded-2xl shadow-sm border border-base-content/5 cursor-grab active:cursor-grabbing hover:border-primary/30 hover:shadow-md transition-all group">
    <div class="flex justify-between items-start mb-2">
        <div class="text-xs font-black text-base-content/40 tracking-widest uppercase">#{{ $ticket->id }}</div>
        <span class="text-[10px] uppercase font-black tracking-widest px-2 py-0.5 rounded-full {{ 
            $ticket->priority == 'urgent' ? 'bg-error/10 text-error' : (
            $ticket->priority == 'high' ? 'bg-warning/10 text-warning' : (
            $ticket->priority == 'medium' ? 'bg-info/10 text-info' : 'bg-base-content/5 text-base-content/40'))
        }}">
            {{ $ticket->priority }}
        </span>
    </div>

    <h4 class="font-bold text-sm text-base-content group-hover:text-primary transition-colors leading-tight mb-3">
        <a href="{{ route('user.tickets.show', $ticket->id) }}" target="_blank" class="hover:underline">
            {{ $ticket->title }}
        </a>
    </h4>

    <div class="flex items-center justify-between mt-auto pt-3 border-t border-base-content/5">
        <div class="flex items-center gap-2" title="Assignee">
            @if($ticket->assignedTo)
                <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center text-[10px] font-black text-primary">
                    {{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}
                </div>
            @else
                <div class="w-6 h-6 rounded-full bg-base-200 border border-dashed border-base-content/20 flex items-center justify-center text-base-content/40">
                    <x-heroicon-o-user class="w-3 h-3" />
                </div>
            @endif
        </div>
        
        <div class="text-xs font-bold text-base-content/50" title="Requester">
            Req: {{ $ticket->user->name }}
        </div>
    </div>
</div>
