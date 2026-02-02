<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
  public function index()
  {
    $openTicketsCount = \App\Models\Ticket::where('status', 'open')->count();
    $myPendingTicketsCount = \App\Models\Ticket::where('assigned_to_id', auth()->id())
        ->whereIn('status', ['open', 'in_progress'])
        ->count();
    $resolvedTicketsCount = \App\Models\Ticket::where('status', 'closed')->count();

    $tickets = \App\Models\Ticket::with(['user', 'department'])->latest()->get();

    return view('dashboard.admin', compact(
        'openTicketsCount', 
        'myPendingTicketsCount', 
        'resolvedTicketsCount', 
        'tickets'
    ));
  }
}
