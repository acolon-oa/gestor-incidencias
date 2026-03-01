<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketUpdated;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        Comment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => auth()->id(),
            'content'   => $request->content,
        ]);

        $ticket->load(['user', 'department', 'assignedTo']);

        // Notify relevant parties about the new comment
        $commenterId = auth()->id();

        // Notify the ticket owner (if not the one commenting)
        if ($ticket->user_id !== $commenterId) {
            $ticket->user?->notify(new TicketUpdated($ticket, 'comment_added'));
        }

        // Notify all admins (if commenter is not admin, notify all admins; if commenter is admin, notify other admins)
        if (auth()->user()->hasRole('admin')) {
            // Admin commenting → notify other admins and the ticket owner (already notified above)
            User::role('admin')
                ->where('id', '!=', $commenterId)
                ->each(fn($admin) => $admin->notify(new TicketUpdated($ticket, 'comment_added')));
        } else {
            // User commenting → notify all admins
            User::role('admin')
                ->each(fn($admin) => $admin->notify(new TicketUpdated($ticket, 'comment_added')));
        }

        // Notify assigned agent if different from commenter and ticket owner
        if (
            $ticket->assigned_to_id &&
            $ticket->assigned_to_id !== $commenterId &&
            $ticket->assigned_to_id !== $ticket->user_id
        ) {
            $ticket->assignedTo?->notify(new TicketUpdated($ticket, 'comment_added'));
        }

        return back()->with('success', 'Comment added.');
    }
}
