<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Mail\TicketCreatedMail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\TicketCreated;

class TicketController extends Controller
{
    public function index()
    {
        return Inertia::render('Tickets/Index', [
            'tickets' => Ticket::where('user_id', Auth::id())
                ->with('comments.user')
                ->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Tickets/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'Open',
        ]);

        $user = Auth::user(); // the user creating the ticket
        $user->notify(new TicketCreated($ticket));


        return redirect()->route('tickets.index')->with('message', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket->load('comments.user'),
        ]);
    }
}
