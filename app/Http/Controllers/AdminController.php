<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
  public function index(Request $request)
  {
    $query = \App\Models\Ticket::with(['user', 'department']);

    if ($request->filled('ticket_id')) {
        $query->where('id', $request->ticket_id);
    }

    if ($request->filled('status') && $request->status !== 'All') {
        $status = strtolower(str_replace(' ', '_', $request->status));
        if ($status === 'resolved') $status = 'closed';
        $query->where('status', $status);
    }

    $tickets = $query->latest()->get();

    $openTicketsCount = \App\Models\Ticket::where('status', 'open')->count();
    $myPendingTicketsCount = \App\Models\Ticket::where('assigned_to_id', auth()->id())
        ->whereIn('status', ['open', 'in_progress'])
        ->count();
    $resolvedTicketsCount = \App\Models\Ticket::where('status', 'closed')->count();

    return view('dashboard.admin', compact(
        'openTicketsCount', 
        'myPendingTicketsCount', 
        'resolvedTicketsCount', 
        'tickets'
    ));
  }
}
