<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubEvent;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SubEventController extends Controller
{
    public function createSubevent($id){
        $event = Event::findOrFail($id);
        $subEvents = $event->sub_events;
        return view("subevents.createSubevent", ['event' => $event, 'subEvents' => $subEvents]);
    }

    public function storeSubevent(Request $request, $id){
        $subEvent = new SubEvent;

        $request->validate([
            'title' => 'required|max:255',
            'date' => 'required',
            'local' => 'required|max:255',
            'description' => 'required',
        ]);

        $subEvent->title = $request->title;
        $subEvent->date = $request->date;
        $subEvent->local = $request->local;
        $subEvent->size = $request->size;
        $subEvent->description = $request->description;

        $event = Event::findOrFail($id);
        $subEvent->event_id = $event->id;
        $subEvent->save();
        return redirect('/')->with('msg', 'Subevento criado com sucesso para '. $event->title);
    }

    public function joinSubevent($event_id, $subevent_id) {
        $user = Auth::user();
        $subevent = SubEvent::findOrFail($subevent_id);

        
        $user->subEventRegistration()->attach($subevent->id);
        

        return back()->with('msg', "Sua presença no ". $subevent->title . " está confirmada!");
    }

}
