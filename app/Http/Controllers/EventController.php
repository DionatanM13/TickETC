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
        return view("events/create");
    }

    public function store(Request $request){
        $event = new Event;

        $validatedData = $request->validate([
            'title' => 'required|string|max:255', // Título obrigatório, texto, no máximo 255 caracteres
            'date' => 'required|date|after_or_equal:today', // Data obrigatória, não pode ser no passado
            'time' => 'required|date_format:H:i', // Hora obrigatória no formato correto
            'finalDate' => 'nullable|date|after_or_equal:date', // Data final opcional, mas deve ser após a data inicial
            'city' => 'required|string|max:255', // Cidade obrigatória, texto, no máximo 255 caracteres
            'local' => 'required|string|max:255', // Local obrigatório, texto, no máximo 255 caracteres
            'size' => 'required|integer', // Tamanho obrigatório, deve ser um número inteiro maior ou igual a 1
            'private' => 'required|boolean', // Campo privado obrigatório, deve ser booleano
            'dominio' => 'nullable|string|max:255', // Domínio opcional, texto, no máximo 255 caracteres
            'description' => 'required|string', // Descrição obrigatória, texto
            'categories' => 'required|array|min:1', // Categorias obrigatórias, devem ser um array com pelo menos 1 categoria
            'categories.*' => 'string|max:50', // Cada categoria deve ser uma string com no máximo 50 caracteres
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Imagem obrigatória, deve ser um arquivo do tipo JPG, JPEG ou PNG, no máximo 2MB
        ], [
            'title.required' => 'O título do evento é obrigatório.',
            'date.required' => 'A data do evento é obrigatória.',
            'date.after_or_equal' => 'A data do evento deve ser a partir da data de hoje.',
            'categories.required' => 'Selecione pelo menos uma categoria para o evento.',
            'image.required' => 'A imagem do evento é obrigatória.',
            'image.mimes' => 'A imagem deve ser do tipo JPG, JPEG ou PNG.',
            'image.max' => 'A imagem deve ter no máximo 2MB.',
        ]);
        
        // Validação personalizada para domínio
        if ($validatedData['private'] && !empty($validatedData['dominio'])) {
            if (!filter_var('test@' . $validatedData['dominio'], FILTER_VALIDATE_EMAIL)) {
                return back()->withErrors(['dominio' => 'O domínio fornecido é inválido.'])->withInput();
            }
        }

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
        return redirect('/')->with('msg-bom', 'Evento Criado com sucesso!');
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

        $subEventsGroupedByDate = $subEvents->groupBy(function($subEvent) {
            return \Carbon\Carbon::parse($subEvent->date)->format('d/m/Y'); // Formato de data (dia/mês/ano)
        });

        return view("events/show", [
            'event' => $event, 
            'eventOwner' => $eventOwner ? $eventOwner->toArray() : null, 
            'subEventsGroupedByDate' => $subEventsGroupedByDate,
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

        return view('events/dashboard', [
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
        

        return view('events/reports', ['event' => $event]);
    }

    public function destroy($id){
        $event = Event::findOrFail($id);
        if($event->image){
            unlink(public_path('img/events/' . $event->image));
        }
        $event->delete();
        return redirect('/dashboard')->with('msg-bom', 'Evento excluído com sucesso!');
    }

    public function edit($id) {

        $user = Auth::user();
        $event = Event::findOrFail($id);

        if($user->id != $event->user_id){
            return redirect('/dashboard');
        }

        return view('/events/edit', ['event' => $event]);
    }

    public function update(Request $request){
        $event = Event::findOrFail($request->id);
        $data = $request->except('days');

        $validatedData = $request->validate([
            'title' => 'required|string|max:255', // Título obrigatório, texto, no máximo 255 caracteres
            'date' => 'required|date|after_or_equal:today', // Data obrigatória, não pode ser no passado
            'finalDate' => 'nullable|date|after_or_equal:date', // Data final opcional, mas deve ser após a data inicial
            'city' => 'required|string|max:255', // Cidade obrigatória, texto, no máximo 255 caracteres
            'local' => 'required|string|max:255', // Local obrigatório, texto, no máximo 255 caracteres
            'size' => 'required|integer', // Tamanho obrigatório, deve ser um número inteiro maior ou igual a 1
            'private' => 'required|boolean', // Campo privado obrigatório, deve ser booleano
            'dominio' => 'nullable|string|max:255', // Domínio opcional, texto, no máximo 255 caracteres
            'description' => 'required|string', // Descrição obrigatória, texto
            'categories' => 'required|array|min:1', // Categorias obrigatórias, devem ser um array com pelo menos 1 categoria
            'categories.*' => 'string|max:50', // Cada categoria deve ser uma string com no máximo 50 caracteres
        ], [
            'title.required' => 'O título do evento é obrigatório.',
            'date.required' => 'A data do evento é obrigatória.',
            'date.after_or_equal' => 'A data do evento deve ser a partir da data de hoje.',
            'categories.required' => 'Selecione pelo menos uma categoria para o evento.',
            'image.mimes' => 'A imagem deve ser do tipo JPG, JPEG ou PNG.',
            'image.max' => 'A imagem deve ter no máximo 2MB.',
        ]);
        
        // Validação personalizada para domínio
        if ($validatedData['private'] && !empty($validatedData['dominio'])) {
            if (!filter_var('test@' . $validatedData['dominio'], FILTER_VALIDATE_EMAIL)) {
                return back()->withErrors(['dominio' => 'O domínio fornecido é inválido.'])->withInput();
            }
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            unlink(public_path('img/events/' . $event->image));
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestImage->move(public_path('img/events'), $imageName);
            $data['image'] = $imageName;
        }

        Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg-bom', 'Evento editado com sucesso!');
    }

    public function joinEvent($event_id, $ticket_id)
    {
        $user = Auth::user();

        // Validações
        $event = Event::findOrFail($event_id);
        $ticket = Ticket::where('id', $ticket_id)
            ->where('event_id', $event_id) // Verifica se o ticket pertence ao evento
            ->firstOrFail();

        // Verificação se o evento é privado
        if ($event->private) {
            $allowedDomain = $event->dominio;

            // Valida o domínio do e-mail do usuário
            if ($allowedDomain && !str_ends_with($user->email, '@' . $allowedDomain)) {
                return back()->with('msg-ruim', 'Este evento é privado e apenas usuários com o domínio "' . $allowedDomain . '" podem participar.');
            }
        }

        if ($ticket->quantity <= 0) {
            return back()->with('msg-ruim', 'Este ticket está esgotado!');
        }

        // Transação para garantir consistência
        DB::transaction(function () use ($user, $event, $ticket) {
            // Associa o usuário ao evento com o ticket escolhido
            $user->eventsAsParticipant()->attach($event->id, ['ticket_id' => $ticket->id]);

            // Reduz a quantidade do ticket
            $ticket->quantity -= 1;
            $ticket->save();
        });

        return back()->with('msg-bom', "Sua presença no evento '" . $event->title . "' está confirmada!");
    }

    public function leaveEvent($id) {
        $user = Auth::user();
        $event = Event::findOrFail($id);
    
        // Recuperar todos os subeventos do evento
        $subevents = $event->sub_events; // Assumindo que você tem um relacionamento 'subEvents' definido no modelo 'Event'

        // Remover o usuário de todos os subeventos
        foreach ($subevents as $subevent) {
            if ($user->subEventRegistration->contains($subevent->id)) {
                $user->subEventRegistration()->detach($subevent->id); // Remove a inscrição do subevento
            }
        }
        // Recuperar o ticket do usuário para este evento
        $ticket = $user->eventsAsParticipant()->wherePivot('event_id', $event->id)->first()->pivot->ticket_id;
        $ticketRecord = Ticket::findOrFail($ticket);
    
        // Aumentar a quantidade de tickets de volta ao estoque
        $ticketRecord->quantity += 1;
        $ticketRecord->save();
    
        // Remover o usuário do evento
        $user->eventsAsParticipant()->detach($event->id);
    
        return back()->with('msg-bom', "Sua presença no " . $event->title . " foi removida e o ticket foi devolvido!");
    }
    

    public function loadMoreEvents(Request $request)
    {
        $skip = $request->input('skip', 0);
        $limit = $request->input('limit', 9);

        $events = Event::skip($skip)->take($limit)->get();

        return response()->json($events);
    }

}
