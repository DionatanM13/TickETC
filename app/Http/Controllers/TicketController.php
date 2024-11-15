<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function createTicket($id){
        $event = Event::findOrFail($id);
        $tickets = $event->tickets;
        return view("tickets.createTicket", ['event' => $event, 'tickets' => $tickets]);
    }

    public function storeTicket(Request $request, $id){
        $ticket = new Ticket;

        $request->validate([
            'title' => 'required|max:255',
            'batch' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'description' => 'required',
        ]);

        $ticket->title = $request->title;
        $ticket->batch = $request->batch;
        $ticket->price = $request->price;
        $ticket->quantity = $request->quantity;
        $ticket->description = $request->description;

        $event = Event::findOrFail($id);
        $ticket->event_id = $event->id;
        $ticket->save();
        return back()->with('msg', 'Ticket/Ingresso criado com sucesso para '. $event->title);
    }

}
