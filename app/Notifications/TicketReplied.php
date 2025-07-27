<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketReplied extends Notification
{
    use Queueable;

    protected $ticket;
    protected $comment;
    protected $customMessage;

    public function __construct($ticket, $comment = null, $customMessage = null)
    {
        $this->ticket = $ticket;
        $this->comment = $comment;
        $this->customMessage = $customMessage;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->ticket_id,
            'subject' => $this->ticket->subject,
            'message' => $this->customMessage ?? 'A new comment was added to the ticket.',
            'comment' => $this->comment?->content ?? null,
            'commented_by' => auth()->user()->name,
        ];
    }
}
