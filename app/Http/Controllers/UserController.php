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
        
        // Count tickets the user is involved in (own or department)
        $queryBase = \App\Models\Ticket::where(function($q) use ($userId, $deptId) {
            $q->where('user_id', $userId)
              ->orWhere('department_id', $deptId);
        });

        $openTicketsCount = (clone $queryBase)->where('status', 'open')->count();
        $inProgressTicketsCount = (clone $queryBase)->where('status', 'in_progress')->count();
        $resolvedTicketsCount = (clone $queryBase)->where('status', 'closed')->count();

        $query = (clone $queryBase)->with(['department', 'user']);

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
