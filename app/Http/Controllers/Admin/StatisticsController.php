<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // 1. General Metrics
        $totalTickets = Ticket::count();
        $totalUsers = User::count();
        $totalResolved = Ticket::where('status', 'closed')->count();
        $resolutionRate = $totalTickets > 0 ? round(($totalResolved / $totalTickets) * 100, 1) : 0;

        // 2. Tickets by Status
        $statusCounts = Ticket::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // 3. Tickets by Department
        $departmentStats = Department::withCount('tickets')
            ->get()
            ->map(function ($dept) {
                return [
                    'name' => $dept->name,
                    'count' => $dept->tickets_count
                ];
            });

        // 4. Top Technicians (Admins with most resolved tickets)
        $topTechnicians = User::role('admin')
            ->withCount(['assignedTickets' => function($query) {
                $query->where('status', 'closed');
            }])
            ->orderBy('assigned_tickets_count', 'desc')
            ->take(5)
            ->get();

        // 5. Monthly Trend (Tickets per month for the last 6 months)
        $monthlyTrend = Ticket::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 6. User Engagement (Tickets created by department)
        $userEngagement = Department::withCount('users')->get();

        // 7. Recent Activity (Last 7 days)
        $ticketsLast7Days = Ticket::where('created_at', '>=', now()->subDays(7))->count();

        return view('admin.statistics.index', compact(
            'totalTickets',
            'totalUsers',
            'totalResolved',
            'resolutionRate',
            'statusCounts',
            'departmentStats',
            'topTechnicians',
            'ticketsLast7Days',
            'monthlyTrend',
            'userEngagement'
        ));
    }
}
