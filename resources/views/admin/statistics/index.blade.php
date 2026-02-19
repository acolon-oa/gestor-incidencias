@extends('layouts.app')

@section('title', 'System Analytics')

@section('content')
<div class="max-w-screen-2xl mx-auto px-4 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black tracking-tight">Executive Analytics</h1>
            <p class="opacity-60 text-lg mt-1">High-level performance metrics and operational insights.</p>
        </div>
        <div class="flex gap-2">
            <span class="badge badge-lg border-none bg-primary/10 text-primary font-bold px-4 py-6">Last 30 Days</span>
            <span class="badge badge-lg border-none bg-success/10 text-success font-bold px-4 py-6">Live Updates</span>
        </div>
    </div>

    <!-- Top Summary Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
        <div class="bg-base-100 p-8 rounded-3xl border border-base-300 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                    <x-heroicon-o-ticket class="w-6 h-6" />
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">Total volume</span>
            </div>
            <div class="text-4xl font-black mb-1">{{ $totalTickets }}</div>
            <div class="text-xs font-bold {{ $ticketsLast7Days > 0 ? 'text-success' : 'opacity-40' }}">
                {{ $ticketsLast7Days }} new this week
            </div>
        </div>

        <div class="bg-base-100 p-8 rounded-3xl border border-base-300 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-success/10 flex items-center justify-center text-success">
                    <x-heroicon-o-check-badge class="w-6 h-6" />
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">Performance</span>
            </div>
            <div class="text-4xl font-black text-success mb-1">{{ $resolutionRate }}%</div>
            <div class="text-xs font-bold opacity-40 uppercase tracking-tighter">Overall resolution rate</div>
        </div>

        <div class="bg-base-100 p-8 rounded-3xl border border-base-300 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-info/10 flex items-center justify-center text-info">
                    <x-heroicon-o-users class="w-6 h-6" />
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">Audience</span>
            </div>
            <div class="text-4xl font-black mb-1">{{ $totalUsers }}</div>
            <div class="text-xs font-bold opacity-40 uppercase tracking-tighter">Total registered users</div>
        </div>

        <div class="bg-base-100 p-8 rounded-3xl border border-base-300 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-warning/10 flex items-center justify-center text-warning">
                    <x-heroicon-o-clock class="w-6 h-6" />
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">Backlog</span>
            </div>
            <div class="text-4xl font-black text-warning mb-1">{{ ($statusCounts['open'] ?? 0) + ($statusCounts['in_progress'] ?? 0) }}</div>
            <div class="text-xs font-bold opacity-40 uppercase tracking-tighter">Active incidents</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <div class="bg-base-100 p-8 rounded-3xl border border-base-300 shadow-sm">
            <h3 class="font-black opacity-60 uppercase text-xs tracking-[0.2em] mb-8">Volume Trend (Last 6 Months)</h3>
            <div class="h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="bg-base-100 p-8 rounded-3xl border border-base-300 shadow-sm">
            <h3 class="font-black opacity-60 uppercase text-xs tracking-[0.2em] mb-8">Ticket Status Mix</h3>
            <div class="h-64 flex justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-3 bg-base-100 rounded-3xl border border-base-300 shadow-sm overflow-hidden flex flex-col">
            <div class="p-8 border-b border-base-300">
                <h3 class="font-black opacity-60 uppercase text-xs tracking-[0.2em]">Incidents by Department</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th class="font-black uppercase text-[10px] tracking-widest pl-8 py-4">Department Name</th>
                            <th class="font-black uppercase text-[10px] tracking-widest py-4">Current Load</th>
                            <th class="font-black uppercase text-[10px] tracking-widest py-4 pr-8 text-right">Activity Bar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base-300">
                        @foreach($departmentStats as $dept)
                            <tr class="hover:bg-base-200 transition-colors border-none">
                                <td class="pl-8 py-5 font-bold opacity-70">{{ $dept['name'] }}</td>
                                <td class="py-5 font-black text-lg">{{ $dept['count'] }}</td>
                                <td class="py-5 pr-8 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <div class="w-64 bg-base-200 h-2 rounded-full overflow-hidden">
                                            @php $p = $totalTickets > 0 ? ($dept['count'] / $totalTickets) * 100 : 0; @endphp
                                            <div class="bg-primary h-full" style="width: {{ $p }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-black opacity-40 w-8">{{ round($p) }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Detect theme to adjust chart colors
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        const textColor = isDark ? 'rgba(255, 255, 255, 0.6)' : 'rgba(0, 0, 0, 0.6)';

        Chart.defaults.color = textColor;

        // Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyTrend->pluck('month')->map(fn($m) => date('F', mktime(0, 0, 0, $m, 10)))) !!},
                datasets: [{
                    label: 'New Incidents',
                    data: {!! json_encode($monthlyTrend->pluck('total')) !!},
                    borderColor: '#6366f1',
                    backgroundColor: isDark ? 'rgba(99, 102, 241, 0.2)' : 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6366f1',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: gridColor }, beginAtZero: true },
                    x: { grid: { display: false } }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Open', 'In Progress', 'Resolved'],
                datasets: [{
                    data: [{{ $statusCounts['open'] ?? 0 }}, {{ $statusCounts['in_progress'] ?? 0 }}, {{ $statusCounts['closed'] ?? 0 }}],
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { weight: 'bold' } } }
                }
            }
        });
    });
</script>
@endsection
