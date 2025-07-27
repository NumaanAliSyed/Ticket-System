@component('mail::message')
# New Ticket Created

A new ticket has been created with the following details:

- **Subject**: {{ $ticket->subject }}
- **Priority**: {{ $ticket->priority }}
- **Description**: {{ $ticket->description }}
- **Status**: {{ $ticket->status }}

@component('mail::button', ['url' => route('tickets.show', $ticket->ticket_id)])
View Ticket
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent
