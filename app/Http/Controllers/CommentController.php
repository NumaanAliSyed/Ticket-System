<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id() && Auth::user()->user_role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        Comment::create([
            'ticket_id' => $ticket->ticket_id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'is_internal' => false,
        ]);

        return redirect()->route('tickets.show', $ticket)->with('message', 'Comment added successfully.');
    }
}