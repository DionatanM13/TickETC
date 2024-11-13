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
            <p class="event-city">
                <ion-icon name="location-outline"></ion-icon> {{$event->city}} </p>
            <p class="events-participants"> <ion-icon name="people-outline">
                </ion-icon> {{count($event->users)}} participantes
            </p>
            <p class="event-owner"><ion-icon name="star-outline">
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
                            <h2>Ingressos Disponíveis</h2>
                            @foreach($tickets as $ticket)
                                <div class="ticket">
                                    <h3>{{ $ticket->title }} (Lote {{ $ticket->batch }})</h3>
                                    <p>Preço: R${{ number_format($ticket->price, 2, ',', '.') }}</p>
                                    <p>Descrição: {{ $ticket->description }}</p>
                                    <p>Quantidade disponível: {{ $ticket->quantity }}</p>
                                    
                                    <form action="/events/join/{{$event->id}}/{{$ticket->id}}" method="POST">
                                        @csrf
                                        <a href="/events/join/{{$event->id}}" 
                                        class="btn btn-primary" 
                                        id="event-submit"
                                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                        Comprar Ingresso
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
                <p>Para participar ou adicionar subeventos, <a href="{{ route('login') }}"> faça login</a>.</p>
            @endif

            @if (count($subEvents) > 0)
                <!-- Botão para exibir subeventos -->
                <button onclick="toggleSubEvents()" class="btn btn-primary">Mostrar Subeventos</button>

                <!-- Seção para exibir subeventos -->
                <div id="subevents-container" style="display: none; margin-top: 20px;">
                    <h3>Subeventos:</h3>
                    <ul>
                        @foreach($subEvents as $subEvent)
                            <li>
                                <strong>{{ $subEvent->title }}</strong> - {{ $subEvent->description }}
                            </li>
                            @if ($hasUserJoined)
                            <form action="/events/join/{{$event->id}}/{{$ticket->id}}" method="POST">
                                        @csrf
                                        <a href="/events/join/{{$event->id}}" 
                                        class="btn btn-primary" 
                                        id="event-submit"
                                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                        Participar
                                        </a> 
                                    </form>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif


            <h3>O evento conta com:</h3>
            <ul id="items-list">
                @foreach ($event->categories as $categorie)
                    <li> <ion-icon name="play-outline"></ion-icon> <span>{{$categorie}}</span></li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-12" id="description-container">
            <h3>Sobre o evento</h3>
            <p class="event-description">{{$event->description}}</p>
        </div>
    </div>
</div>

@endsection