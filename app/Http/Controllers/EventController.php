<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(){

        $search = request('search');
        $search_category = request('search-category');

        $currentDate = now(); // Obtém a data e hora atual
        
        if ($search) {
        // Filtra por título e eventos futuros, ordenando por data
        $events = Event::where('title', 'like', '%' . $search . '%')
            ->whereRaw('CONCAT(date, " ", time) >= ?', [$currentDate])
            ->orderByRaw('CONCAT(date, " ", time) ASC')
            ->get();
    } elseif ($search_category) {
        // Filtra por categoria e eventos futuros, ordenando por data
        $events = Event::where('categories', 'like', '%' . $search_category . '%')
            ->whereRaw('CONCAT(date, " ", time) >= ?', [$currentDate])
            ->orderByRaw('CONCAT(date, " ", time) ASC')
            ->get();
    } else {
        // Todos os eventos futuros, ordenados por data
        $events = Event::whereRaw('CONCAT(date, " ", time) >= ?', [$currentDate])
            ->orderByRaw('CONCAT(date, " ", time) ASC')
            ->get();
    }

        return view("index", ['events' => $events, 'search' => $search, 'category' => $search_category]);
    }

    public function create(){
        return view("events.create");
    }

    public function store(Request $request){
        $event = new Event;

        $request->validate([
            'title' => 'required|max:255',
            'city' => 'required|max:255',
            'private' => 'required|boolean',
            'description' => 'required',
        ]);
        
        $event->title = $request->title;
        $event->date = $request->date;
        $event->time = $request->time;
        $event->finalDate = $request->finalDate;
        $event->city = $request->city;
        $event->local = $request->local;
        $event->size = $request->size;
        $event->private = $request->private;
        $event->dominio = $request->dominio;
        $event->description = $request->description;
        $event->categories = $request->categories;

        //upload de imagem
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")). "." . $extension;

            $requestImage->move(public_path("img/events"), $imageName);
            $event->image = $imageName;
        }

        $user = Auth::user();
        $event->user_id = $user->id;
        $event->save();
        return redirect('/')->with('msg', 'Evento Criado com sucesso!');
    }


    public function show($id) {
        $event = Event::with(['sub_events', 'tickets', 'users'])->findOrFail($id);
        $eventOwner = User::find($event->user_id);
        $subEvents = $event->sub_events;
        $tickets = Ticket::where('event_id', $id)->avaiableLowestBatch();

        $user = Auth::user();
        $hasUserJoined = false;
        $userTicket = null;

        $subEventsUser = [];

        if($user){
            // Verifica se o usuário está participando do evento
            $hasUserJoined = $event->users->contains($user);
            $subEventsUser = $user->subEventRegistration->pluck('id')->toArray();

            if ($hasUserJoined) {
                // Recupera o ingresso do usuário
                $pivotData = $user->eventsAsParticipant->firstWhere('id', $id)->pivot ?? null;

                if ($pivotData && isset($pivotData->ticket_id)) {
                    $userTicket = Ticket::find($pivotData->ticket_id);
                }
            }
        }


        return view("events.show", [
            'event' => $event, 
            'eventOwner' => $eventOwner ? $eventOwner->toArray() : null, 
            'subEvents' => $subEvents,
            'hasUserJoined' => $hasUserJoined,
            'tickets' => $tickets,
            'userTicket' => $userTicket,
            'subEventsUser' => $subEventsUser
        ]);
    }

    public function dashboard() {
        $user = Auth::user();

        // Info Managers
        $events = $user->events;


        // Info Participants
        $eventsAsParticipant = $user->eventsAsParticipant;
        $subEventRegistration = $user->subEventRegistration;

        return view('events.dashboard', [
            'events' => $events, 
            'eventsAsParticipant' => $eventsAsParticipant, 
            'subEventRegistration' => $subEventRegistration
        ]);
    }

    public function eventReports($event_id){
        $event = Event::with(['users', 'sub_events', 'tickets'])->findOrFail($event_id);

        foreach ($event->tickets as $ticket) {
            $ticket->sold_count = 0; // Inicializa quantidade vendida
            $ticket->revenue = 0;    // Inicializa receita
        
            if ($event->users) {
                $ticket->sold_count = $event->users
                                            ->where('pivot.ticket_id', $ticket->id)
                                            ->count();
                $ticket->revenue = $ticket->sold_count * $ticket->price;
            }
        }
        

        return view('events.reports', ['event' => $event]);
    }

    public function destroy($id){
        $event = Event::findOrFail($id);
        if($event->image){
            unlink(public_path('img/events/' . $event->image));
        }
        $event->delete();
        return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');
    }

    public function edit($id) {

        $user = Auth::user();
        $event = Event::findOrFail($id);

        if($user->id != $event->user_id){
            return redirect('/dashboard');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request){
        $event = Event::findOrFail($request->id);
        $data = $request->except('days');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            unlink(public_path('img/events/' . $event->image));
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestImage->move(public_path('img/events'), $imageName);
            $data['image'] = $imageName;
        }
        Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');
    }

    public function joinEvent($event_id, $ticket_id)
{
    $user = Auth::user();

    // Validações
    $event = Event::findOrFail($event_id);
    $ticket = Ticket::where('id', $ticket_id)
        ->where('event_id', $event_id) // Verifica se o ticket pertence ao evento
        ->firstOrFail();

    if ($ticket->quantity <= 0) {
        return back()->with('error', 'Este ticket está esgotado!');
    }

    // Transação para garantir consistência
    DB::transaction(function () use ($user, $event, $ticket) {
        // Associa o usuário ao evento com o ticket escolhido
        $user->eventsAsParticipant()->attach($event->id, ['ticket_id' => $ticket->id]);

        // Reduz a quantidade do ticket
        $ticket->quantity -= 1;
        $ticket->save();
    });

    return back()->with('msg', "Sua presença no evento '" . $event->title . "' está confirmada!");
}

    public function leaveEvent($id){
        $user = Auth::user();
        $event = Event::findOrFail($id);
        $user->eventsAsParticipant()->detach($id);

        return back()->with('msg', "Sua presença no " . $event->title . " foi removida!");

    }

}
