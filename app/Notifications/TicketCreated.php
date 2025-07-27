<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketCreated extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database']; // Only database notification
    }

    public function toDatabase($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->ticket_id,
            'subject' => $this->ticket->subject,
            'priority' => $this->ticket->priority,
            'message' => 'A new ticket has been created.',
            'created_by' => auth()->user()->name ?? 'System',
        ];
    }
}
