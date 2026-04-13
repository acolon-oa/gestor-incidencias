<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Console\Command;

class CloseInactiveTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:close-inactive-tickets {days=7 : The number of days of inactivity before closing}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Automatically close tickets with no activity for a specified number of days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->argument('days');
        $threshold = now()->subDays($days);

        $tickets = Ticket::where('status', '!=', 'closed')
            ->where('updated_at', '<', $threshold)
            ->get();

        if ($tickets->isEmpty()) {
            $this->info('No inactive tickets found.');
            return;
        }

        $count = 0;
        foreach ($tickets as $ticket) {
            $ticket->update([
                'status' => 'closed',
                'closed_at' => now(),
            ]);

            AuditLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => null, // System action
                'type' => 'status_change',
                'old_value' => 'in_progress',
                'new_value' => 'closed',
            ]);

            Comment::create([
                'ticket_id' => $ticket->id,
                'user_id' => null, // System comment
                'content' => "This ticket has been automatically closed due to inactivity for more than {$days} days. If you still need help, please open a new ticket.",
                'is_internal' => false,
            ]);

            $count++;
        }

        $this->info("Successfully closed {$count} inactive tickets.");
    }
}
