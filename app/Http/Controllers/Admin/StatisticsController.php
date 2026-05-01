<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StatisticsController extends Controller
{
    private function getStatisticsData()
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



        // 5. Monthly Trend (Tickets per month for the last 6 months)
        $monthlyTrend = Ticket::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();



        // 7. Recent Activity (Last 7 days)
        $ticketsLast7Days = Ticket::where('created_at', '>=', now()->subDays(7))->count();

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
        return view('admin.statistics.index', $this->getStatisticsData());
    }

    public function exportPdf()
    {
        $pdf = Pdf::loadView('admin.statistics.pdf', $this->getStatisticsData());
        return $pdf->download('statistics-' . now()->format('Y-m-d') . '.pdf');
    }
}
