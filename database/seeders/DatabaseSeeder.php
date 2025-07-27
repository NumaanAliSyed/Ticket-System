<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'user_role' => 'user',
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'user_role' => 'admin',
        ]);

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'subject' => 'Test Ticket',
            'description' => 'This is a test ticket.',
            'priority' => 'High',
            'status' => 'Open',
        ]);

        Comment::create([
            'ticket_id' => $ticket->ticket_id,
            'user_id' => $user->id,
            'content' => 'Please help with this issue.',
            'is_internal' => false,
        ]);

        Comment::create([
            'ticket_id' => $ticket->ticket_id,
            'user_id' => $admin->id,
            'content' => 'Working on it.',
            'is_internal' => false,
        ]);

        Comment::create([
            'ticket_id' => $ticket->ticket_id,
            'user_id' => $admin->id,
            'content' => 'Internal note: Check logs.',
            'is_internal' => true,
        ]);
    }
}