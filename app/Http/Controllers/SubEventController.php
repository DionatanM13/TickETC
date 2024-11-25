<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubEvent;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubEventController extends Controller
{
    public function createSubevent($id){
        $event = Event::findOrFail($id);
        $subEvents = $event->sub_events;
        return view("subevents.createSubevent", ['event' => $event, 'subEvents' => $subEvents]);
    }

    public function storeSubevent(Request $request, $id){
        $subEvent = new SubEvent;
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today', // Data deve ser hoje ou futura
            'time' => 'required|date_format:H:i', // Verifica o formato de hora
            'finalTime' => 'required|date_format:H:i|after:time', // FinalTime deve ser após Time
            'local' => 'required|string|max:255',
            'size' => 'required|integer|min:1', // Tamanho deve ser um número positivo
            'description' => 'required|string|max:1000', // Limita tamanho da descrição
        ], [
            'title.required' => 'O título do subevento é obrigatório.',
            'title.max' => 'O título do subevento não pode ter mais que 255 caracteres.',
        
            'date.required' => 'A data do subevento é obrigatória.',
            'date.date' => 'Insira uma data válida.',
            'date.after_or_equal' => 'A data do subevento deve ser hoje ou uma data futura.',
        
            'time.required' => 'O horário inicial do subevento é obrigatório.',
        
            'finalTime.required' => 'O horário final do subevento é obrigatório.',
            'finalTime.after' => 'O horário final deve ser após o horário inicial.',
        
            'local.required' => 'O local do subevento é obrigatório.',
            'local.max' => 'O local do subevento não pode ter mais que 255 caracteres.',
        
            'size.required' => 'O tamanho do subevento é obrigatório.',
            'size.min' => 'O tamanho do subevento deve ser de pelo menos 1 participante.',
        
            'description.required' => 'A descrição do subevento é obrigatória.',
            'description.max' => 'A descrição do subevento não pode ter mais que 1000 caracteres.',
        ]);

        // Validação do tamanho do subevento em relação ao evento pai
        if ($request->size > $event->size) {
            return back()->withErrors(['size' => 'O tamanho do subevento não pode ser maior que o tamanho do evento principal.']);
        }

        // Validação de data do subevento dentro do intervalo do evento pai
        $eventStartDate = $event->date;
        $eventEndDate = $event->finalDate ? $event->finalDate : $event->date;

        if ($request->date < $eventStartDate || $request->date > $eventEndDate) {
            return back()->withErrors(['date' => 'A data do subevento deve ser dentro do intervalo de datas do evento principal.']);
        }

        $subEvent->title = $request->title;
        $subEvent->date = $request->date;
        $subEvent->time = $request->time;
        $subEvent->finalTime = $request->finalTime;
        $subEvent->local = $request->local;
        $subEvent->size = $request->size;
        $subEvent->description = $request->description;

        $event = Event::findOrFail($id);
        $subEvent->event_id = $event->id;
        $subEvent->save();
        return redirect('/events/'. $event->id)->with('msg-bom', 'Subevento criado com sucesso para '. $event->title);
    }

    public function joinSubevent($event_id, $subevent_id) {
        $user = Auth::user();
        $subevent = SubEvent::findOrFail($subevent_id);
    
        // Verificar se o usuário já está participando deste subevento
        $userSubevents = $user->subEventRegistration->toArray();
        foreach ($userSubevents as $userSubevent) {
            if ($userSubevent['id'] == $subevent->id) {
                return back()->with('msg-ruim', "Você já está participando desse subevento.");
            }
        }
    
        // Verificar se há conflitos de datas e horários com outros subeventos do usuário
        foreach ($userSubevents as $userSubevent) {
            $existingSubevent = SubEvent::find($userSubevent['id']);
            
            // Verifica se as datas e horários dos subeventos se sobrepõem
            $existingStartTime = \Carbon\Carbon::parse($existingSubevent->date . ' ' . $existingSubevent->time);
            $existingEndTime = \Carbon\Carbon::parse($existingSubevent->date . ' ' . $existingSubevent->finalTime);
            $newStartTime = \Carbon\Carbon::parse($subevent->date . ' ' . $subevent->time);
            $newEndTime = \Carbon\Carbon::parse($subevent->date . ' ' . $subevent->finalTime);
    
            // Verificar se há sobreposição de horário
            if ($newStartTime < $existingEndTime && $newEndTime > $existingStartTime) {
                return back()->with('msg-ruim', "Você já está inscrito em um subevento neste horário.");
            }
        }
    
        // Associar o usuário ao subevento
        $user->subEventRegistration()->attach($subevent->id);
        $subevent->size--;
        $subevent->save();
    
        return back()->with('msg-bom', "Sua presença no subevento '". $subevent->title . "' está confirmada!");
    }
    

    public function leaveSubevent($event_id, $subevent_id)
    {
        $user = Auth::user();
        $subevent = SubEvent::findOrFail($subevent_id);

        // Verificar se o usuário está realmente participando do subevento
        if ($user->subEventRegistration->contains($subevent_id)) {
            $user->subEventRegistration()->detach($subevent_id);

            $subevent->size++;
            $subevent->save();
            return back()->with('msg-bom', "Você saiu do subevento: " . $subevent->title);
        }

        return back()->with('msg-ruim', "Você não está participando deste subevento.");
    }


    public function destroy($event_id, $subevent_id){
        $subevent = SubEvent::findOrFail($subevent_id);
        $subevent->delete();

        return back()->with('msg-bom', "Sub-evento excluído com sucesso!");
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
    
        return redirect('/dashboard')->with('msg-bom', 'Sub-evento editado com sucesso!');
    }

}
