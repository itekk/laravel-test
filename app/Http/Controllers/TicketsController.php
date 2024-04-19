<?php

namespace App\Http\Controllers;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Http\Requests\Tickets\CreateTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

/**
 * Suggestion: Add authorization checks for create, store, edit, update, and destroy methods to ensure only authorized users can perform these actions.
 */
class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return inertia('Tickets/Index', [
            'tickets' => $tickets,
            'totalItems' => $tickets->count(), 
        ]);
    }

    /**
     * Filter tickets based on the provided request filters and paginate the results.
     */
    public function filterTickets(Request $request)
    {
        $filters = $request->all();
        $tickets = Ticket::filter($filters)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page);
        
        return response()->json([
            'tickets' => $tickets->items(),
            'totalItems' => $tickets->total(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Tickets/CreateTicket');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTicketRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();

        Ticket::create($data);

        return redirect()->route('tickets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::with('user', 'responses')->findOrFail($id);
        return inertia('Tickets/Show', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * Suggestion: Implement editing functionality for the specified ticket.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * 
     * Suggestion: Implement update functionality for the specified ticket.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return redirect()->route('tickets.index');
    }
}
