<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    // Listar todos los tickets
    public function index()
    {
        $tickets = Ticket::with(['user', 'department', 'assignedTo'])->latest()->paginate(15);
        return view('admin.tickets.index', compact('tickets'));
    }

    // Mostrar ticket
    public function show(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    // Formulario crear ticket
    public function create()
    {
        $users = \App\Models\User::all(); // Todos los usuarios para asignar o seleccionar
        $departments = \App\Models\Department::all();
        $roles = \Spatie\Permission\Models\Role::all(); // Para crear usuario con rol
        return view('admin.tickets.create', compact('users', 'departments', 'roles'));
    }

    // Guardar ticket
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'status'      => 'required|in:open,in_progress,closed',
            'priority'    => 'required|in:low,medium,high,urgent',
            'department_id' => 'required|exists:departments,id',
            'user_id'     => 'required|exists:users,id', // Valid requester is mandatory
            'assigned_to_id' => 'nullable|exists:users,id',
            'assign_to_me' => 'nullable|string', // Checkbox handling
        ]);

        $assignedToId = $request->assigned_to_id;
        if ($request->has('assign_to_me')) {
            $assignedToId = auth()->id();
        }

        Ticket::create([
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => $request->status ?? 'open',
            'priority'    => $request->priority ?? 'low',
            'department_id' => $request->department_id,
            'user_id'     => $request->user_id, // Requester
            'assigned_to_id' => $assignedToId, // Assigned Agent
        ]);

        return redirect()->route('admin.tickets.index')
                         ->with('success', 'Ticket created successfully.');
    }
}