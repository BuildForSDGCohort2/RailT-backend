<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::with('schedule')->get();
        return response(['ticket' => new TicketResource($tickets), 'message' => 'Ticket Retrieved Success'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ticket' => 'required|unique:tickets',
            'schedule' => 'required',
        ]);
        if ($data) {
            $ticket = Ticket::create($data);
            return response(['ticket' => new TicketResource($ticket), 'message' => 'Ticket registered Successfully'], 200);
        }
        else {
            return response()->json(['errors' => $data->errors()->all()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        return response(['ticket' => new TicketResource($ticket), 'message' => 'Retrieved Success'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        $ticket->update($request->only('ticket', 'schedule'));
        return response([ 'ticket' => new TicketResource($ticket), 'message' => 'Updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return response()->json(['message' => null], 204);
    }
}
