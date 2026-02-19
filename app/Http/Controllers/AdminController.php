<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
  public function index(Request $request)
  {
    $user = auth()->user();
    $query = \App\Models\Ticket::with(['user', 'department']);

    // Admin sees all, but non-admin (even if they have access to this dashboard, 
    // though the route is protected) would see only their department.
    // However, the main request is: admin sees all, others only see their department.
    if (!$user->hasRole('admin')) {
        $query->where('department_id', $user->department_id);
    }

    // Exclude resolved (closed) tickets from the main list by default as per user preference
    if (!$request->filled('status')) {
        $query->whereIn('status', ['open', 'in_progress']);
    } elseif ($request->status !== 'All') {
        $status = strtolower(str_replace(' ', '_', $request->status));
        if ($status === 'resolved') $status = 'closed';
        $query->where('status', $status);
    }

    if ($request->filled('ticket_id')) {
        $query->where('id', $request->ticket_id);
    }

    $tickets = $query->latest()->get();

    $user = auth()->user();
    $openTicketsCount = \App\Models\Ticket::where('status', 'open')
        ->when(!$user->hasRole('admin'), fn($q) => $q->where('department_id', $user->department_id))
        ->count();
    $myPendingTicketsCount = \App\Models\Ticket::where('assigned_to_id', $user->id)
        ->whereIn('status', ['open', 'in_progress'])
        ->count();
    $resolvedTicketsCount = \App\Models\Ticket::where('status', 'closed')
        ->when(!$user->hasRole('admin'), fn($q) => $q->where('department_id', $user->department_id))
        ->count();

    return view('dashboard.admin', compact(
        'openTicketsCount', 
        'myPendingTicketsCount', 
        'resolvedTicketsCount', 
        'tickets'
    ));
  }
}
