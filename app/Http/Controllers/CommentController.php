<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
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
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Comentario añadido.');
    }
}
