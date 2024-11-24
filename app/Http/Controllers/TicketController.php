<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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


    public function destroy($event_id, $ticket_id){
        $ticket = Ticket::findOrFail($ticket_id);
        $ticket->delete();

        return back()->with('msg', "Ticket excluído com sucesso!");
    }

    public function edit($event_id, $ticket_id) {

        $user = Auth::user();
        $event = Event::findOrFail($event_id);
        $ticket = Ticket::findOrFail($ticket_id);

        if($user->id != $event->user_id){
            return redirect('/dashboard');
        }

        return view('tickets.editTicket', ['ticket' => $ticket, 'event' => $event]);
    }

    public function update(Request $request, $event_id, $ticket_id) {
        $ticket = Ticket::findOrFail($ticket_id); // Agora usa o ticket_id da rota
        $data = $request->all();
        $event = Event::findOrFail($event_id); // Agora usa o event_id da rota
    
        $ticket->update($data); // Atualiza os dados do ticket
    
        return redirect('/dashboard')->with('msg', 'Ticket editado com sucesso!');
    }
    
}
