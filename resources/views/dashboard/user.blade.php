@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 mt-2">
        <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-error/10 flex items-center justify-center text-error group-hover:scale-110 transition-transform">
                    <x-heroicon-o-exclamation-circle class="w-6 h-6" />
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-base-content/20">Active</span>
            </div>
            <div class="text-4xl font-black text-base-content tracking-tighter">{{ $openTicketsCount }}</div>
            <div class="text-xs font-bold text-base-content/40 mt-1">Open Tickets</div>
        </div>
        <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-warning/10 flex items-center justify-center text-warning group-hover:scale-110 transition-transform">
                    <x-heroicon-o-arrow-path class="w-6 h-6" />
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-base-content/20">Progress</span>
            </div>
            <div class="text-4xl font-black text-base-content tracking-tighter">{{ $inProgressTicketsCount }}</div>
            <div class="text-xs font-bold text-base-content/40 mt-1">In Progress</div>
        </div>
        <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-success/10 flex items-center justify-center text-success group-hover:scale-110 transition-transform">
                    <x-heroicon-o-check-circle class="w-6 h-6" />
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-base-content/20">Done</span>
            </div>
            <div class="text-4xl font-black text-base-content tracking-tighter">{{ $resolvedTicketsCount }}</div>
            <div class="text-xs font-bold text-base-content/40 mt-1">Resolved</div>
        </div>
    </div>

    <!-- Tickets Table Card -->
    <div class="bg-base-100 border border-base-content/5 rounded-3xl shadow-sm overflow-hidden">
        <div class="flex flex-col sm:flex-row items-center justify-between px-8 py-6 border-b border-base-content/5 gap-4">
            <div>
                <h2 class="text-lg font-black text-base-content tracking-tight">Recent Activity</h2>
                <p class="text-xs text-base-content/40 mt-0.5 font-medium italic">Latest updates on your reported incidents</p>
            </div>
            
            <form action="{{ route('user.dashboard') }}" method="GET" class="flex items-center gap-2">
                <div class="join border border-base-content/10 rounded-2xl overflow-hidden shadow-sm bg-base-100">
                    <input type="text" name="ticket_id" value="{{ request('ticket_id') }}" placeholder="Search ID..." class="input input-sm join-item w-28 bg-transparent focus:outline-none text-xs font-bold" />
                    <select name="status" class="select select-sm join-item bg-transparent focus:outline-none text-xs font-bold border-l border-base-content/10">
                        <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All Status</option>
                        <option value="Open" {{ request('status') == 'Open' || (!request()->has('status') && !request()->has('ticket_id')) ? 'selected' : '' }}>Only Open</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved Only</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary join-item px-6 font-bold">Search</button>
                </div>
                @if(request()->anyFilled(['ticket_id', 'status']))
                    <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-ghost btn-circle" title="Reset filters">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="bg-base-200/50 text-base-content/40 border-b border-base-content/5">
                        <th class="font-black uppercase text-[10px] tracking-widest py-5 pl-8">ID</th>
                        <th class="font-black uppercase text-[10px] tracking-widest py-5">Subject</th>
                        <th class="font-black uppercase text-[10px] tracking-widest py-5 text-center">Department</th>
                        <th class="font-black uppercase text-[10px] tracking-widest py-5 text-center">Status</th>
                        <th class="font-black uppercase text-[10px] tracking-widest py-5 pr-8 text-right">Last Updated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-content/5 font-medium">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-primary/5 transition-colors cursor-pointer group"
                            onclick="window.location='{{ route('user.tickets.show', $ticket->id) }}'">
                            <td class="pl-8 font-black text-base-content/20 text-sm">#{{ $ticket->id }}</td>
                            <td>
                                <div class="font-bold text-base-content text-sm group-hover:text-primary transition-colors">{{ $ticket->title }}</div>
                                <div class="text-[10px] uppercase font-black tracking-widest mt-0.5 {{
                                    $ticket->priority == 'urgent' ? 'text-error' : (
                                    $ticket->priority == 'high' ? 'text-warning' : (
                                    $ticket->priority == 'medium' ? 'text-info' : 'text-base-content/30'))
                                }}">{{ $ticket->priority }} priority</div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-ghost badge-sm font-bold opacity-60 border-base-content/10">{{ $ticket->department->name ?? 'N/A' }}</span>
                            </td>
                            <td class="text-center">
                                @if($ticket->status == 'open')
                                    <span class="badge badge-error badge-sm font-black text-[10px]">OPEN</span>
                                @elseif($ticket->status == 'in_progress')
                                    <span class="badge badge-warning badge-sm font-black text-[10px]">IN PROGRESS</span>
                                @elseif($ticket->status == 'closed')
                                    <span class="badge badge-success badge-sm font-black text-[10px] text-white">CLOSED</span>
                                @endif
                            </td>
                            <td class="text-xs text-base-content/40 pr-8 text-right font-bold">{{ $ticket->updated_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-24">
                                <div class="w-16 h-16 bg-base-200 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                    <x-heroicon-o-ticket class="w-8 h-8 text-base-content/20" />
                                </div>
                                <p class="text-base-content/40 font-bold italic">No tickets found.</p>
                                <a href="{{ route('user.tickets.create') }}" class="btn btn-link btn-sm text-primary font-black mt-2">Submit your first report</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
