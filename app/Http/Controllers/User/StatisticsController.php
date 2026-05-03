<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StatisticsController extends Controller
{
    private function getStatisticsData($departmentId = null)
    {
        $ticketQuery = Ticket::query();
        if ($departmentId) {
            $ticketQuery->where('department_id', $departmentId);
        }

        // 1. General Metrics
        $totalTickets = (clone $ticketQuery)->count();
        $totalUsers = $departmentId 
            ? User::where('department_id', $departmentId)->count() 
            : User::count();
        $totalResolved = (clone $ticketQuery)->where('status', 'closed')->count();
        $resolutionRate = $totalTickets > 0 ? round(($totalResolved / $totalTickets) * 100, 1) : 0;

        // 2. Tickets by Status
        $statusCounts = (clone $ticketQuery)->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // 3. Tickets by Department
        $departmentStats = Department::withCount(['tickets' => function($query) use ($departmentId) {
            if ($departmentId) {
                $query->where('department_id', $departmentId);
            }
        }])
            ->when($departmentId, function($query) use ($departmentId) {
                return $query->where('id', $departmentId);
            })
            ->get()
            ->map(function ($dept) {
                return [
                    'name' => $dept->name,
                    'count' => $dept->tickets_count
                ];
            });

        // 5. Monthly Trend (Tickets per month for the last 6 months)
        $monthlyTrend = (clone $ticketQuery)->select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 7. Recent Activity (Last 7 days)
        $ticketsLast7Days = (clone $ticketQuery)->where('created_at', '>=', now()->subDays(7))->count();

        return compact(
            'totalTickets',
            'totalUsers',
            'totalResolved',
            'resolutionRate',
            'statusCounts',
            'departmentStats',
            'ticketsLast7Days',
            'monthlyTrend'
        );
    }

    public function index()
    {
        $departmentId = auth()->user()->department_id;
        return view('user.statistics.index', $this->getStatisticsData($departmentId));
    }

    public function exportPdf()
    {
        $departmentId = auth()->user()->department_id;
        $pdf = Pdf::loadView('admin.statistics.pdf', $this->getStatisticsData($departmentId));
        return $pdf->download('my-department-statistics-' . now()->format('Y-m-d') . '.pdf');
    }
}
