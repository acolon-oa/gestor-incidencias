@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="w-full max-w-3xl mx-auto py-8">

    <div class="flex items-center justify-between mb-10 px-4">
        <div>
            <h1 class="text-3xl font-extrabold text-base-content tracking-tight">Notifications</h1>
            <p class="text-sm text-base-content/40 mt-1 font-medium">Stay up to date with your tickets and organization activity.</p>
        </div>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm font-black text-primary hover:bg-primary/10 rounded-xl px-4">
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-8 rounded-2xl border-none shadow-sm bg-green-500/10 text-green-500 py-4">
            <x-heroicon-o-check-circle class="w-6 h-6" />
            <span class="font-bold tracking-tight">{{ session('success') }}</span>
        </div>
    @endif

    <div class="space-y-4 px-4">
        @forelse($notifications as $notification)
            @php
                $data = $notification->data;
                $isRead = $notification->read_at !== null;
                $iconColor = match($data['type'] ?? '') {
                    'ticket_created' => 'text-primary bg-primary/10',
                    'ticket_updated' => match($data['change_type'] ?? '') {
                        'status_changed' => 'text-warning bg-warning/10',
                        'comment_added'  => 'text-info bg-info/10',
                        'assigned'       => 'text-success bg-success/10',
                        default          => 'text-base-content/40 bg-base-content/5',
                    },
                    default => 'text-base-content/40 bg-base-content/5',
                };
            @endphp
            <div class="flex items-start gap-5 p-6 rounded-3xl border transition-all {{ $isRead ? 'bg-base-100 border-base-content/5 opacity-80' : 'bg-primary/5 border-primary/20 shadow-md shadow-primary/5' }}">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $iconColor }}">
                    @if(($data['change_type'] ?? '') === 'status_changed')
                        <x-heroicon-o-arrow-path class="w-6 h-6" />
                    @elseif(($data['change_type'] ?? '') === 'comment_added')
                        <x-heroicon-o-chat-bubble-left class="w-6 h-6" />
                    @elseif(($data['change_type'] ?? '') === 'assigned')
                        <x-heroicon-o-user-circle class="w-6 h-6" />
                    @elseif(($data['type'] ?? '') === 'ticket_created')
                        <x-heroicon-o-ticket class="w-6 h-6" />
                    @else
                        <x-heroicon-o-bell class="w-6 h-6" />
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                        <div class="space-y-1">
                            <p class="text-sm font-black text-base-content leading-tight">
                                {{ $data['message'] ?? 'Notification received' }}
                            </p>
                            <div class="flex flex-wrap items-center gap-3">
                                @if(isset($data['ticket_id']))
                                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.tickets.show', $data['ticket_id']) : route('user.tickets.show', $data['ticket_id']) }}"
                                       class="text-xs text-primary font-black hover:underline tracking-tight">
                                        View Incident #{{ $data['ticket_id'] }}
                                    </a>
                                @endif
                                @if(isset($data['priority']))
                                    @php
                                        $pColor = match($data['priority']) {
                                            'urgent' => 'text-error', 'high' => 'text-warning',
                                            'medium' => 'text-info', default => 'text-base-content/20',
                                        };
                                    @endphp
                                    <span class="text-[10px] font-black uppercase {{ $pColor }} tracking-widest">{{ $data['priority'] }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            @if(!$isRead)
                                <span class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse flex-shrink-0"></span>
                            @endif
                            <span class="text-[10px] text-base-content/40 font-black italic whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                @if(!$isRead)
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-circle btn-sm text-base-content/20 hover:text-primary hover:bg-primary/10 transition-colors" title="Mark as read">
                            <x-heroicon-o-check class="w-4 h-4" />
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="text-center py-24 bg-base-100 border border-base-content/5 rounded-3xl">
                <div class="w-16 h-16 rounded-3xl bg-base-200 flex items-center justify-center mx-auto mb-4">
                    <x-heroicon-o-bell-slash class="w-8 h-8 text-base-content/20" />
                </div>
                <p class="text-base-content/40 font-black italic">You're all caught up!</p>
                <p class="text-xs text-base-content/20 font-medium">Check back later for new updates.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-8 px-4">
            {{ $notifications->links() }}
        </div>
    @endif

</div>
@endsection
