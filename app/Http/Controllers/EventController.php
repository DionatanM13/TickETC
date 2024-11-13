<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EventController extends Controller
{
    public function index(){

        $search = request('search');

        if($search){
            $events = Event::where([
                ['title', 'like', '%'.$search.'%']
            ])->get();
        }else{
            $events = Event::all();
        }

        return view("index", ['events' => $events, 'search' => $search]);
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
        $event = Event::findOrFail($id);
        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        $subEvents = $event->sub_events;

        $tickets = Ticket::where('event_id', $id)->avaiableLowestBatch();

        $user = Auth::user();
        $hasUserJoined = false;

        if($user){
            $userEvents = $user->eventsAsParticipant->toArray();
            foreach($userEvents as $userEvent){
                if($userEvent['id'] == $id){
                    $hasUserJoined = true;
                }
            }

            $userSubevents = $user->subEventRegistration->toArray();
        }


        return view("events.show", [
            'event' => $event, 
            'eventOwner' => $eventOwner, 
            'subEvents' => $subEvents,
            'hasUserJoined' => $hasUserJoined,
            'tickets' => $tickets,
            'userSubevents' => $userSubevents
        ]);
    }

    public function dashboard() {
        $user = Auth::user();
        $events = $user->events;

        $eventsAsParticipant = $user->eventsAsParticipant;
        return view('events.dashboard', ['events' => $events, 'eventsAsParticipant' => $eventsAsParticipant]);
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
        $data = $request->all();

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

    public function joinEvent($event_id, $ticket_id) {
        $user = Auth::user();

        $event = Event::findOrFail($event_id);
        $ticket = Ticket::findOrFail($ticket_id);

        $user->eventsAsParticipant()->attach($event_id, ['ticket_id' => $ticket_id]);
        $ticket->quantity -= 1;
        $ticket->save();

        return back()->with('msg', "Sua presença no " . $event->title . " está confirmada!");
    }

    public function leaveEvent($id){
        $user = Auth::user();
        $event = Event::findOrFail($id);
        $user->eventsAsParticipant()->detach($id);

        return back()->with('msg', "Sua presença no " . $event->title . " foi removida!");

    }
    // SUBEVENTOS
    


}
