<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketCreated;
use App\Notifications\TicketUpdated;
use App\Models\AuditLog;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $user = auth()->user();
        if (!$user->hasRole('admin') && $ticket->department_id !== $user->department_id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $ticket->load(['user', 'department', 'comments.user', 'assignedTo', 'attachments', 'auditLogs.user']);
        $cannedResponses = \App\Models\CannedResponse::all();
        return view('admin.tickets.show', compact('ticket', 'cannedResponses'));
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
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);

        $ticket = Ticket::create([
            'title'          => $request->title,
            'description'    => $request->description,
            'status'         => $request->status ?? 'open',
            'priority'       => $request->priority ?? 'low',
            'department_id'  => $request->department_id,
            'user_id'        => auth()->id(), // Becomes the requester
            'assigned_to_id' => null, // Starts unassigned
        ]);

        AuditLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'type' => 'ticket_created',
            'new_value' => 'Ticket created by assistant/admin',
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/' . $ticket->id, 'public');
                Attachment::create([
                    'ticket_id' => $ticket->id,
                    'user_id'   => auth()->id(),
                    'filename'  => $file->getClientOriginalName(),
                    'path'      => $path,
                    'mime_type' => $file->getMimeType(),
                    'size'      => $file->getSize(),
                ]);
            }
        }

        // Notify all other admins of new ticket
        $ticket->load(['user', 'department']);
        User::role('admin')
            ->where('id', '!=', auth()->id())
            ->each(fn($admin) => $admin->notify(new TicketCreated($ticket)));

        $successMessage = 'Ticket created successfully.';
        if ($request->hasFile('attachments')) {
            $successMessage .= ' ' . count($request->file('attachments')) . ' files attached.';
        }

        return redirect()->route('admin.dashboard')
                         ->with('success', $successMessage);
    }

    // Actualizar ticket (estado, asignación, etc)
    public function update(Request $request, Ticket $ticket)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin') && $ticket->department_id !== $user->department_id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $validated = $request->validate([
            'status' => 'nullable|in:open,in_progress,closed',
            'assigned_to_id' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'department_id' => 'nullable|exists:departments,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $oldStatus = $ticket->status;
        $oldPriority = $ticket->priority;
        $oldAssignedTo = $ticket->assigned_to_id;
        $oldDeptId = $ticket->department_id;

        if (isset($validated['department_id']) && $validated['department_id'] != $ticket->department_id) {
            $validated['assigned_to_id'] = null;
            
            AuditLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'type' => 'department_change',
                'old_value' => $ticket->department?->name,
                'new_value' => \App\Models\Department::find($validated['department_id'])?->name,
            ]);
        }

        if (isset($validated['assigned_to_id']) && $validated['assigned_to_id'] !== $oldAssignedTo) {
             AuditLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'type' => 'assignment_change',
                'old_value' => $ticket->assignedTo?->name ?? 'Unassigned',
                'new_value' => User::find($validated['assigned_to_id'])?->name ?? 'Unassigned',
            ]);
        }

        if (isset($validated['status']) && $validated['status'] !== $oldStatus) {
             AuditLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'type' => 'status_change',
                'old_value' => $oldStatus,
                'new_value' => $validated['status'],
            ]);
        }

        if (isset($validated['priority']) && $validated['priority'] !== $oldPriority) {
             AuditLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'type' => 'priority_change',
                'old_value' => $oldPriority,
                'new_value' => $validated['priority'],
            ]);
        }

        if (isset($validated['status']) && $validated['status'] === 'closed') {
            $ticket->closed_at = now();
        }

        $ticket->update($validated);
        $ticket->load(['user', 'department', 'assignedTo']);

        // Notify the requester of status changes (if not the one making the change)
        if (isset($validated['status']) && $validated['status'] !== $oldStatus) {
            if ($ticket->user_id !== auth()->id()) {
                $ticket->user?->notify(new TicketUpdated(
                    $ticket,
                    'status_changed',
                    $oldStatus,
                    $validated['status']
                ));
            }
            // Also notify all admins except the one who made the change
            User::role('admin')
                ->where('id', '!=', auth()->id())
                ->each(fn($admin) => $admin->notify(new TicketUpdated($ticket, 'status_changed', $oldStatus, $validated['status'])));
        }

        // Notify the newly assigned agent
        $newAssignedTo = $validated['assigned_to_id'] ?? $ticket->assigned_to_id;
        if (
            isset($validated['assigned_to_id']) &&
            $validated['assigned_to_id'] !== $oldAssignedTo &&
            $newAssignedTo &&
            $newAssignedTo !== auth()->id()
        ) {
            $ticket->assignedTo?->notify(new TicketUpdated($ticket, 'assigned'));
        }

        return redirect()->route('admin.tickets.show', $ticket->id)->with('success', 'Ticket updated successfully.');
    }

    // Eliminar ticket
    public function destroy(Ticket $ticket)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin') && $ticket->department_id !== $user->department_id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

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

    public function exportPdf(Ticket $ticket)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin') && $ticket->department_id !== $user->department_id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $ticket->load(['user', 'department', 'comments.user', 'assignedTo']);
        $pdf = Pdf::loadView('admin.tickets.pdf', compact('ticket'));
        return $pdf->download('ticket-' . $ticket->id . '.pdf');
    }
}