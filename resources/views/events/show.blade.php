@extends('layouts.main')

@section('title', $event->title)

@section('content')

<div class="container mt-5">
    <div class="row">
        <div id="image-container" class="col-md-6">
            <img src="/img/events/{{$event->image}}" class="img-fluid rounded" alt="{{$event->title}}">
        </div>
        <div id="info-container" class="col-md-6">
            <h1 class="mb-3 title-show">{{$event->title}}</h1>

            <!-- Categorias -->
            <div class="d-flex flex-wrap mb-3" id="category-list">
                @foreach ($event->categories as $category)
                    <div class="badge bg-secondary m-1">{{ $category }}</div>
                @endforeach
            </div>

            <!-- Informações do evento -->
            <p class="event-info">
                <ion-icon name="location-outline" class="me-2"></ion-icon> {{$event->city}} - {{$event->local}} 
            </p>
            <p class="event-info"> 
                <ion-icon name="calendar-outline" class="me-2"></ion-icon> 
                {{date('d/m/Y', strtotime($event->date))}} 
                @if ($event->finalDate)
                    - {{date('d/m/Y', strtotime($event->finalDate))}}
                @endif
                <ion-icon name="time-outline" class="ms-3 me-2"></ion-icon>
                {{date('H:i', strtotime($event->time))}}
            </p>
            <p class="event-info">
                <ion-icon name="star-outline" class="me-2"></ion-icon> {{$eventOwner['name']}}
            </p>
            
            <!-- Ações do usuário -->
            @if (auth()->check())
                @if (auth()->user()->id == $event->user_id)
                    <a href="/events/{{$event->id}}/tickets/create" class="btn btn-success">
                        <ion-icon name="add-outline"></ion-icon> Gerenciar Ingressos
                    </a>
                    <a href="/events/{{$event->id}}/subevents/create" class="btn btn-info">
                        <ion-icon name="add-outline"></ion-icon> Gerenciar Subeventos
                    </a>
                @else
                    @if (!$hasUserJoined)
                        @if ($tickets->isNotEmpty())
                            <h5 class="mt-3">Ingressos Disponíveis:</h5>
                            @foreach ($tickets as $ticket)
                                <div class="ticket mb-3 border p-3 rounded d-flex flex-column flex-md-row">
                                    <!-- Detalhes do ingresso -->
                                    <div class="ticket-info flex-grow-1">
                                        <p class="mb-1">
                                            <ion-icon name="ticket-outline" class="me-2"></ion-icon>
                                            <strong>{{ $ticket->title }}</strong> (Lote {{ $ticket->batch }})
                                        </p>
                                        <p class="mb-1">
                                            <strong>Preço:</strong> 
                                            @if($ticket->price == 0)
                                                <strong>Gratuito</strong>
                                            @else
                                                R$ {{ number_format($ticket->price, 2, ',', '.') }}
                                            @endif                                        </p>
                                        <p class="mb-1">{{ $ticket->description }}</p>
                                    </div>

                                    <!-- Botão de compra -->
                                    <div class="ticket-action ms-md-3 mt-3 mt-md-0">
                                        <form action="/events/join/{{$event->id}}/{{$ticket->id}}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-dark">
                                                @if ($ticket->price > 0)
                                                    Comprar  
                                                @else
                                                Participar Gratuitamente
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>Não há ingressos disponíveis para compra no momento.</p>
                        @endif
                    @else
                        <p class="alert alert-info">Você já está participando deste evento!</p>

                        <!-- Mostrar informações do ingresso escolhido -->
                        @if ($userTicket)
                        <div class="ticket-info mt-4 p-4 border rounded shadow-sm bg-light">
                            <h5 class="mb-4 text-primary d-flex align-items-center">
                                <ion-icon name="ticket-outline" class="me-2"></ion-icon> Detalhes do Seu Ingresso
                            </h5>
                            <ul class="list-unstyled mb-0" style="font-size: 16px">
                                <li class="mb-2">
                                    <strong class="text-secondary">Título:</strong> {{ $userTicket->title }}
                                    <span class="badge bg-info text-white ms-2">Lote {{ $userTicket->batch }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong class="text-secondary">Preço:</strong> R${{ number_format($userTicket->price, 2, ',', '.') }}
                                </li>
                                <li class="mb-2">
                                    <strong class="text-secondary">Descrição:</strong> {{ $userTicket->description }}
                                </li>
                            </ul>
                        </div>

                        @endif
                    @endif
                @endif
            @else
                <p id="event-login">Para participar ou adicionar subeventos: <a class="btn btn-dark" href="{{ route('login') }}">Faça Login</a></p>
            @endif


            <!-- Descrição do evento -->
            <div class="col-md-12" id="description-container">
                <h3>Sobre o evento</h3>
                <p class="event-description">{{$event->description}}</p>
            </div>
        </div>
    </div>

    <!-- Subeventos -->
    @if ($subEventsGroupedByDate->isNotEmpty())
        <div class="mt-5" id="subevents-container">
            <h3>Subeventos:</h3>
            
            @foreach ($subEventsGroupedByDate as $date => $subEventsByDate)
                <div class="date-group mt-4">
                    <h4 class="mb-3">{{ $date }}</h4>
                    <div class="row">
                        @foreach ($subEventsByDate as $subEvent)
                            <div class="col-md-6 mb-4">
                                <div class="subevent p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
                                    <!-- Título com destaque -->
                                    <h5 class="mb-3" style="font-size: 1.25rem; font-weight: bold; color:#2e073f;">
                                        {{ $subEvent->title }}
                                    </h5>

                                    <!-- Descrição -->
                                    <p class="mb-2" style="color: #4b2e83;">{{ $subEvent->description }}</p>

                                    <!-- Data e Local com ícones e cores -->
                                    <p class="mb-1 text-muted">
                                        <ion-icon name="calendar-outline" class="me-2"></ion-icon>
                                        <strong>
                                            {{ date('d/m/Y', strtotime($subEvent->date)) }}
                                            <ion-icon name="time-outline" class="ms-3 me-2"></ion-icon>
                                            {{ date('H:i', strtotime($subEvent->time)) }}
                                            -
                                            {{ date('H:i', strtotime($subEvent->finalTime)) }}
                                        </strong>
                                    </p>
                                    <p class="mb-2 text-muted">
                                        <ion-icon name="location-outline" class="me-2"></ion-icon>
                                        <strong>{{ $subEvent->local }}</strong>
                                    </p>

                                    <!-- Vagas restantes -->
                                    <p class="mb-3 text-muted" style="font-size: 1rem; font-weight: 600; color: #e74c3c;">
                                        <ion-icon name="people-outline" class="me-2"></ion-icon>
                                        <strong>Vagas disponíveis:</strong> {{ $subEvent->size }}
                                    </p>

                                    <!-- Botão de Participação ou Mensagem -->
                                    @if ($hasUserJoined)
                                        @if (in_array($subEvent->id, $subEventsUser))
                                            <p class="alert alert-info text-center mb-3">
                                                <ion-icon name="checkmark-circle-outline" class="me-2"></ion-icon>
                                                Você já está participando deste subevento.
                                            </p>

                                            <!-- Botão para sair do subevento -->
                                            <form action="/events/{{$event->id}}/subevents/leave/{{$subEvent->id}}" method="POST" class="text-center">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger w-100">
                                                    <ion-icon name="exit-outline" class="me-2"></ion-icon>
                                                    Sair do Subevento
                                                </button>
                                            </form>
                                        @else
                                            <form action="/events/{{ $event->id }}/subevents/join/{{ $subEvent->id }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100">
                                                    Participar
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif


</div>

@endsection
