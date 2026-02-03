<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // Listar tickets del usuario
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())
                         ->latest()
                         ->paginate(10);

        return view('user.tickets.index', compact('tickets'));
    }

    // Formulario para crear ticket
    public function create()
    {
        return view('user.tickets.create');
    }

    // Guardar ticket
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject'     => 'required|string|max:255',
            'department'  => 'required|string|exists:departments,name',
            'priority'    => 'nullable|string|in:low,medium,high',
            'description' => 'required|string',
        ]);

        $department = \App\Models\Department::where('name', $validated['department'])->first();

        $ticket = Ticket::create([
            'title'         => $validated['subject'],
            'description'   => $validated['description'],
            'priority'      => $validated['priority'] ?? 'low',
            'department_id' => $department->id,
            'user_id'       => Auth::id(),
            'status'        => 'open',
        ]);

        return redirect()->route('user.dashboard')
                         ->with('success', 'Ticket creado correctamente.');
    }

    // Ver ticket
    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $ticket->load(['department', 'comments.user', 'assignedTo']);

        return view('user.tickets.show', compact('ticket'));
    }
}