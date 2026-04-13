<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketUpdated;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'content' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);

        $comment = Comment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => auth()->id(),
            'content'   => $request->content,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/' . $ticket->id, 'public');
                Attachment::create([
                    'ticket_id' => $ticket->id,
                    'comment_id' => $comment->id,
                    'user_id'   => auth()->id(),
                    'filename'  => $file->getClientOriginalName(),
                    'path'      => $path,
                    'mime_type' => $file->getMimeType(),
                    'size'      => $file->getSize(),
                ]);
            }
        }

        $ticket->load(['user', 'department', 'assignedTo']);
        $commenterId = auth()->id();

        // Notify the ticket owner (if not the one commenting)
        if ($ticket->user_id !== $commenterId) {
            $ticket->user?->notify(new TicketUpdated($ticket, 'comment_added', null, $comment->content));
        }

        // Notify admins with details
        if (auth()->user()->hasRole('admin')) {
             User::role('admin')
                ->where('id', '!=', $commenterId)
                ->each(fn($admin) => $admin->notify(new TicketUpdated($ticket, 'comment_added', null, $comment->content)));
        } else {
             User::role('admin')
                ->each(fn($admin) => $admin->notify(new TicketUpdated($ticket, 'comment_added', null, $comment->content)));
        }

        return back();
    }
}
