@extends('layouts.main')

@section('title', $event->title)

@section('content')

<div class="col-md-10 offset-md-1">
    <div class="row">
        <div id="image-container" class="col-md-6">
            <img src="/img/events/{{$event->image}}" class="img-fluid" alt="{{$event->title}}">
        </div>
        <div id="info-container" class="col-md-6">
            <h1>{{$event->title}}</h1>

            <div class="d-flex flex-row" id="category-list">
                @foreach ($event->categories as $category)
                    <div class="p-2 item-category"><span>{{$category}}</span></div>
                @endforeach
            </div>

            <p class="event-info">
                <ion-icon name="location-outline"></ion-icon> {{$event->city}} 
            </p>
            <p class="event-info"> <ion-icon name="calendar-outline">
                </ion-icon> {{date('d/m/Y', strtotime($event->finalDate))}}
            </p>
            <p class="event-info"><ion-icon name="star-outline">
                </ion-icon> {{$eventOwner['name']}}
            </p>
            
            @if (auth()->check())
                @if (auth()->user()->id == $event->user_id)
                    <a href="/events/{{$event->id}}/tickets/create" 
                    class="btn btn-secondary">
                    Adicionar tipo de Ingresso/Ticket
                    </a>
                    <a href="/events/{{$event->id}}/subevents/create" 
                    class="btn btn-secondary">
                    Adicionar Subevento
                    </a>
                @else

                    @if (!$hasUserJoined)
                        @if($tickets->isNotEmpty())
                            <h5 class="">Ingressos Disponíveis:</h5>
                            @foreach($tickets as $ticket)
                                <div class="ticket col-md-12">
                                    <p class="p-3">{{ $ticket->title }} (Lote {{ $ticket->batch }})</p>
                                    <p class="p-2">R${{ number_format($ticket->price, 2, ',', '.') }}</p>
                                    <p class="p-4">{{ $ticket->description }}</p>
                                    
                                    <form action="/events/join/{{$event->id}}/{{$ticket->id}}" method="POST">
                                        @csrf
                                        <a href="/events/join/{{$event->id}}" 
                                        class="btn btn-dark" 
                                        id="event-submit"
                                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                        Comprar
                                        </a> 
                                    </form>

                                </div>
                                <hr>
                            @endforeach
                        @else
                            <p>Não há ingressos disponíveis para compra no momento.</p>
                        @endif
                        
                    @else
                        <p class="already-joined-msg">Você já está participando desde evento!</p>
                    @endif
                @endif                
            @else
                <p id="event-login">Para participar ou adicionar subeventos: <a class="btn btn-dark" href="{{ route('login') }}"> Faça Login</a></p>
            @endif

            <div class="col-md-12" id="description-container">
                <h3>Sobre o evento</h3>
                <p class="event-description">{{$event->description}}</p>
            </div>

        </div>
        

        @if (count($subEvents) > 0)

            <!-- Seção para exibir subeventos -->
            <div class="col-md-12" id="subevents-container">
                <h3>Subeventos:</h3>
                
                    @foreach($subEvents as $subEvent)
                        <div class="d-flex flex-row subevent">
                            <p class="col-md-3"><strong>{{ $subEvent->title }}</strong></p>
                            <p class="col-md-3">{{ $subEvent->description }}</p>
                            <p class="col-md-2">{{date('d/m/Y', strtotime($subEvent->date))}}</p>
                            <p class="col-md-2">{{$subEvent->local}}</p>

                            @if ($hasUserJoined)
                                <form action="/events/{{$event->id}}/subevents/join/{{$subEvent->id}}" method="POST" class="col-md-2">
                                    @csrf
                                    <a href="/events/{{$event->id}}/subevents/join/{{$subEvent->id}}" 
                                    class="btn btn-dark"
                                    id="event-submit"
                                    onclick="event.preventDefault();
                                    this.closest('form').submit();">
                                    Participar
                                    </a> 
                                </form>
                            @endif
                        </div>
                        
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
</div>

@endsection