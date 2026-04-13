<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketCreated;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240', // 10MB limit
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

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/' . $ticket->id, 'public');
                Attachment::create([
                    'ticket_id' => $ticket->id,
                    'user_id'   => Auth::id(),
                    'filename'  => $file->getClientOriginalName(),
                    'path'      => $path,
                    'mime_type' => $file->getMimeType(),
                    'size'      => $file->getSize(),
                ]);
            }
        }

        // Notify all admins that a new ticket has been submitted
        $ticket->load(['user', 'department']);
        User::role('admin')->each(fn($admin) => $admin->notify(new TicketCreated($ticket)));

        return redirect()->route('user.dashboard')
                         ->with('success', 'Ticket submitted successfully. Our team will review it shortly.');
    }

    // Ver ticket
    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $ticket->load(['department', 'comments.user', 'assignedTo']);

        return view('user.tickets.show', compact('ticket'));
    }
}