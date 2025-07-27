<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Notifications\TicketReplied; // or create TicketStatusUpdated


class TicketAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('user');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        return Inertia::render('Admin/Tickets/Index', [
            'tickets' => $query->get(),
            'users' => User::select('id', 'name')->get(),
            'filters' => [
                'status' => $request->status ?? '',
                'priority' => $request->priority ?? '',
                'date' => $request->date ?? '',
                'user_id' => $request->user_id ?? '',
            ],
        ]);
    }

    public function show(Ticket $ticket)
    {
        return Inertia::render('Admin/Tickets/Show', [
            'ticket' => $ticket->load('comments.user', 'user'),
        ]);
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:Open,In Progress,Closed',
        ]);

        $ticket->update(['status' => $validated['status']]);

        $message = 'Ticket status updated to: ' . $validated['status'];

        // Send notification with custom message
        $ticket->user->notify(new TicketReplied($ticket, null, $message));
        auth()->user()->notify(new TicketReplied($ticket, null, $message));


        return redirect()->route('admin.tickets.show', $ticket)->with('message', 'Status updated.');
    }

    public function addInternalRemark(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment  = Comment::create([
            'ticket_id' => $ticket->ticket_id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'is_internal' => true,
        ]);

        $ticket->user->notify(new TicketReplied($ticket, $comment));
        auth()->user()->notify(new TicketReplied($ticket, $comment));

        return redirect()->route('admin.tickets.show', $ticket)->with('message', 'Internal remark added.');
    }
}
