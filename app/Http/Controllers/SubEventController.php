<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubEvent;
use App\Models\Event;

class SubEventController extends Controller
{
    public function createSubevent($id){
        $event = Event::findOrFail($id);
        return view("subevents.createSubevent", ['event' => $event]);
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
}
