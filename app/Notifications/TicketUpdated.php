<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public string $changeType = 'updated', // 'status_changed', 'comment_added', 'assigned', 'updated'
        public ?string $oldValue = null,
        public ?string $newValue = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $message = match($this->changeType) {
            'status_changed' => 'Ticket #' . $this->ticket->id . ' status changed to "' . ucfirst(str_replace('_', ' ', $this->newValue ?? '')) . '"',
            'comment_added'  => 'New comment on ticket #' . $this->ticket->id . ': "' . $this->ticket->title . '"',
            'assigned'       => 'Ticket #' . $this->ticket->id . ' has been assigned to you',
            default          => 'Ticket #' . $this->ticket->id . ' was updated: "' . $this->ticket->title . '"',
        };

        return [
            'type'        => 'ticket_updated',
            'change_type' => $this->changeType,
            'ticket_id'   => $this->ticket->id,
            'title'       => $this->ticket->title,
            'priority'    => $this->ticket->priority,
            'department'  => $this->ticket->department?->name ?? 'N/A',
            'old_value'   => $this->oldValue,
            'new_value'   => $this->newValue,
            'message'     => $message,
        ];
    }
}
