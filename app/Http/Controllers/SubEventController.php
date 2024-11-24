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
        return redirect('/events/'. $event->id)->with('msg', 'Subevento criado com sucesso para '. $event->title);
    }

    public function joinSubevent($event_id, $subevent_id) {
        $user = Auth::user();
        $subevent = SubEvent::findOrFail($subevent_id);

        $userSubevents = $user->subEventRegistration->toArray();
        foreach($userSubevents as $userSubevent){
            if ($userSubevent['id'] == $subevent->id){
                return back()->with('msg', "Você já está participando desse subevento");
            }
        }
        
        $user->subEventRegistration()->attach($subevent->id);
        

        return back()->with('msg', "Sua presença no ". $subevent->title . " está confirmada!");
    }

    public function destroy($event_id, $subevent_id){
        $subevent = SubEvent::findOrFail($subevent_id);
        $subevent->delete();

        return back()->with('msg', "Sub-evento excluído com sucesso!");
    }

    public function edit($event_id, $subevent_id) {

        $user = Auth::user();
        $event = Event::findOrFail($event_id);
        $subevent = SubEvent::findOrFail($subevent_id);

        if($user->id != $event->user_id){
            return redirect('/dashboard');
        }

        return view('subevents.editSubevent', ['subevent' => $subevent, 'event' => $event]);
    }

    public function update(Request $request, $event_id, $subevent_id) {
        $subevent = SubEvent::findOrFail($subevent_id); 
        $data = $request->all();
        $event = Event::findOrFail($event_id);
    
        $subevent->update($data); 
    
        return redirect('/dashboard')->with('msg', 'Sub-evento editado com sucesso!');
    }

}
