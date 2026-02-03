<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        $openTicketsCount = \App\Models\Ticket::where('user_id', $userId)->where('status', 'open')->count();
        $inProgressTicketsCount = \App\Models\Ticket::where('user_id', $userId)->where('status', 'in_progress')->count();
        $resolvedTicketsCount = \App\Models\Ticket::where('user_id', $userId)->where('status', 'closed')->count();

        $tickets = \App\Models\Ticket::with('department')
            ->where('user_id', $userId)
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
