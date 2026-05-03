<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;
        $deptId = $user->department_id;
        $view = $request->get('view', 'department'); // Default to department for normal dashboard

        // Base query for counts (all visible tickets)
        $countQuery = \App\Models\Ticket::where(function($q) use ($userId, $deptId) {
            $q->where('user_id', $userId)
              ->orWhere('department_id', $deptId);
        });

        // Query for the specific view
        if ($view === 'personal') {
            $query = \App\Models\Ticket::where('user_id', $userId);
        } else {
            $query = \App\Models\Ticket::where('department_id', $deptId);
        }

        $openTicketsCount = (clone $countQuery)->where('status', 'open')->count();
        $inProgressTicketsCount = (clone $countQuery)->where('status', 'in_progress')->count();
        $resolvedTicketsCount = (clone $countQuery)->where('status', 'closed')->count();

        $query->with(['department', 'user']);

        // Filtering logic (same as Admin)
        if ($request->filled('ticket_id')) {
            $query->where('id', $request->ticket_id);
        }

        if ($request->filled('status') && $request->status !== 'All') {
            $status = strtolower(str_replace(' ', '_', $request->status));
            if ($status === 'resolved') $status = 'closed';
            $query->where('status', $status);
        } else if (!$request->anyFilled(['status', 'ticket_id'])) {
            // Default: show open and in_progress as "Active" tickets (requested previously)
            $query->whereIn('status', ['open', 'in_progress']);
        }

        $tickets = $query->latest()->get();

        return view('dashboard.user', compact(
            'openTicketsCount',
            'inProgressTicketsCount',
            'resolvedTicketsCount',
            'tickets'
        ));
    }
}
