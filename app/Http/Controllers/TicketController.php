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
        return view("/tickets/createTicket", ['event' => $event, 'tickets' => $tickets]);
    }

    public function storeTicket(Request $request, $id){
        $ticket = new Ticket;

        $request->validate([
            'title' => 'required|max:255',  // Título é obrigatório e não pode ter mais que 255 caracteres
            'batch' => 'required|integer|min:1',  // Lote é obrigatório, deve ser um número inteiro maior ou igual a 1
            'price' => 'nullable|required_if:is_free,0|numeric|min:0.01',  // O valor deve ser numérico e maior que 0,01
            'quantity' => 'required|integer|min:1',  // A quantidade deve ser um número inteiro maior ou igual a 1
            'description' => 'required|max:1000',  // Descrição obrigatória com no máximo 1000 caracteres
        ], [
            'title.required' => 'O título do ingresso é obrigatório.',
            'title.max' => 'O título do ingresso não pode ter mais que 255 caracteres.',
            
            'batch.required' => 'O lote do ingresso é obrigatório.',
            'batch.integer' => 'O lote deve ser um número inteiro.',
            'batch.min' => 'O lote deve ser no mínimo 1.',
            
            'price.required_if' => 'O valor do ingresso é obrigatório para eventos pagos.',
            'price.numeric' => 'O valor do ingresso deve ser um número.',
            'price.min' => 'O valor do ingresso não pode ser menor que 0.',
            
            'quantity.required' => 'A quantidade disponível do ingresso é obrigatória.',
            'quantity.integer' => 'A quantidade deve ser um número inteiro.',
            'quantity.min' => 'A quantidade disponível deve ser no mínimo 1.',
            
            'description.required' => 'A descrição do ingresso é obrigatória.',
            'description.max' => 'A descrição do ingresso não pode ter mais que 1000 caracteres.',
        ]);
        

        $ticket->title = $request->title;
        $ticket->batch = $request->batch;
        $ticket->quantity = $request->quantity;
        $ticket->description = $request->description;

        if ($request->has('is_free') && $request->is_free) {
            $ticket->price = 0.00; // Define preço como zero para eventos gratuitos
        } else {
            $ticket->price = $request->price;
        }

        $event = Event::findOrFail($id);
        $ticket->event_id = $event->id;
        $ticket->save();
        return back()->with('msg-bom', 'Ticket/Ingresso criado com sucesso para '. $event->title);
    }


    public function destroy($event_id, $ticket_id){
        $ticket = Ticket::findOrFail($ticket_id);
        $ticket->delete();

        return back()->with('msg-bom', "Ticket excluído com sucesso!");
    }

    public function edit($event_id, $ticket_id) {

        $user = Auth::user();
        $event = Event::findOrFail($event_id);
        $ticket = Ticket::findOrFail($ticket_id);

        if($user->id != $event->user_id){
            return redirect('/dashboard');
        }

        return view('/tickets/editTicket', ['ticket' => $ticket, 'event' => $event]);
    }

    public function update(Request $request, $event_id, $ticket_id) {
        $ticket = Ticket::findOrFail($ticket_id); // Agora usa o ticket_id da rota
        $data = $request->all();
        $event = Event::findOrFail($event_id); // Agora usa o event_id da rota
    
        $ticket->update($data); // Atualiza os dados do ticket
    
        return redirect('/dashboard')->with('msg-bom', 'Ticket editado com sucesso!');
    }
    
}
