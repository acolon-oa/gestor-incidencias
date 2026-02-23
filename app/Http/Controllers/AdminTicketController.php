<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    // Listar todos los tickets
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Ticket::with(['user', 'department', 'assignedTo']);

        if (!$user->hasRole('admin')) {
            $query->where('department_id', $user->department_id);
        }

        if ($request->filled('ticket_id')) {
            $query->where('id', $request->ticket_id);
        }

        if ($request->filled('status') && $request->status !== 'All') {
            $status = strtolower(str_replace(' ', '_', $request->status));
            if ($status === 'resolved') $status = 'closed';
            $query->where('status', $status);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.tickets.index', compact('tickets'));
    }

    public function edit(Ticket $ticket)
    {
        return redirect()->route('admin.tickets.show', $ticket->id);
    }

    // Mostrar ticket
    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'department', 'comments.user', 'assignedTo']);
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

        return redirect()->route('admin.dashboard')
                         ->with('success', 'Ticket created successfully.');
    }

    // Actualizar ticket (estado, asignación, etc)
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:open,in_progress,closed',
            'assigned_to_id' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'department_id' => 'nullable|exists:departments,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if (isset($validated['department_id']) && $validated['department_id'] != $ticket->department_id) {
            // If department changes, we must unassign the current user as they might not belong to the new department
            $validated['assigned_to_id'] = null;
        }

        if (isset($validated['assigned_to_id']) && $validated['assigned_to_id'] !== null) {
            // Verify the user belongs to the ticket's department (or new department)
            $targetDeptId = $validated['department_id'] ?? $ticket->department_id;
            $user = \App\Models\User::find($validated['assigned_to_id']);
            if ($user->department_id && $user->department_id != $targetDeptId) {
                return back()->withErrors(['assigned_to_id' => 'The selected agent does not belong to the correct department.']);
            }
        }

        if (isset($validated['status']) && $validated['status'] === 'closed') {
            $ticket->closed_at = now();
        }

        $ticket->update($validated);

        return redirect()->route('admin.tickets.show', $ticket->id)->with('success', 'Ticket updated successfully.');
    }

    // Eliminar ticket
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Ticket deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:tickets,id',
        ]);

        Ticket::whereIn('id', $request->ticket_ids)->delete();

        return redirect()->route('admin.dashboard')->with('success', count($request->ticket_ids) . ' tickets deleted successfully.');
    }
}