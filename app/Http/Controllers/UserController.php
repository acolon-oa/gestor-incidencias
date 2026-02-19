<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;
        $deptId = $user->department_id;
        
        // Count tickets the user is involved in (own or department)
        $queryCounts = \App\Models\Ticket::where(function($q) use ($userId, $deptId) {
            $q->where('user_id', $userId)
              ->orWhere('department_id', $deptId);
        });

        $openTicketsCount = (clone $queryCounts)->where('status', 'open')->count();
        $inProgressTicketsCount = (clone $queryCounts)->where('status', 'in_progress')->count();
        $resolvedTicketsCount = (clone $queryCounts)->where('status', 'closed')->count();

        $tickets = \App\Models\Ticket::with(['department', 'user'])
            ->where(function($q) use ($userId, $deptId) {
                $q->where('user_id', $userId)
                  ->orWhere('department_id', $deptId);
            })
            ->latest()
            ->get();

        return view('dashboard.user', compact(
            'openTicketsCount',
            'inProgressTicketsCount',
            'resolvedTicketsCount',
            'tickets'
        ));
    }
}
