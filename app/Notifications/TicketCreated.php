<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketCreated extends Notification
{
    use Queueable;

    public function __construct(public Ticket $ticket)
    {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'ticket_created',
            'ticket_id'  => $this->ticket->id,
            'title'      => $this->ticket->title,
            'priority'   => $this->ticket->priority,
            'department' => $this->ticket->department?->name ?? 'N/A',
            'creator'    => $this->ticket->user?->name ?? 'Unknown',
            'message'    => 'New ticket submitted: "' . $this->ticket->title . '"',
        ];
    }
}
