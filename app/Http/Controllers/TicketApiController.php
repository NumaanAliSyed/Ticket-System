    <?php

    namespace App\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\Ticket;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use App\Notifications\TicketCreated;


    class TicketApiController extends Controller
    {
        public function index()
        {
            return response()->json(Ticket::where('user_id', Auth::id())->with('comments.user')->get());
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

            return response()->json($ticket, 201);
        }
    }