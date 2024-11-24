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
                        <ion-icon name="add-outline"></ion-icon> Adicionar tipo de Ingresso
                    </a>
                    <a href="/events/{{$event->id}}/subevents/create" class="btn btn-info">
                        <ion-icon name="add-outline"></ion-icon> Adicionar Subevento
                    </a>
                @else
                    @if (!$hasUserJoined)
                        @if($tickets->isNotEmpty())
                            <h5 class="mt-3">Ingressos Disponíveis:</h5>
                            @foreach($tickets as $ticket)
                                <div class="ticket mb-3 border p-3 rounded d-flex flex-column flex-md-row">
                                    <!-- Detalhes do ingresso -->
                                    <div class="ticket-info flex-grow-1">
                                        <p class="mb-1">
                                            <ion-icon name="ticket-outline" class="me-2"></ion-icon>
                                            <strong>{{ $ticket->title }}</strong> (Lote {{ $ticket->batch }})
                                        </p>
                                        <p class="mb-1">
                                            <strong>Preço:</strong> R${{ number_format($ticket->price, 2, ',', '.') }}
                                        </p>
                                        <p class="mb-1">{{ $ticket->description }}</p>
                                    </div>

                                    <!-- Botão de compra -->
                                    <div class="ticket-action ms-md-3 mt-3 mt-md-0">
                                        <form action="/events/join/{{$event->id}}/{{$ticket->id}}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-dark">
                                                Comprar
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
    @if (count($subEvents) > 0)
        <div class="mt-5" id="subevents-container">
            <h3>Subeventos:</h3>
            <div class="row">
            @foreach($subEvents as $subEvent)
                <div class="col-md-6 mb-4">
                    <div class="subevent p-4 border rounded shadow-sm">
                        <!-- Título com destaque -->
                        <h5 class="mb-3" style="font-size: 1.25rem; font-weight: bold; color:#2e073f">
                            {{ $subEvent->title }}
                        </h5>

                        <!-- Descrição -->
                        <p class="mb-2" style="color: #2e073f;">{{ $subEvent->description }}</p>

                        <!-- Data e Local com ícones e cores -->
                        <p class="mb-1 text-muted">
                            <ion-icon name="calendar-outline" class="me-2"></ion-icon>
                            <strong>{{date('d/m/Y', strtotime($subEvent->date))}}</strong>
                        </p>
                        <p class="mb-3 text-muted">
                            <ion-icon name="location-outline" class="me-2"></ion-icon>
                            <strong>{{ $subEvent->local }}</strong>
                        </p>

                        <!-- Botão de Participação -->
                        @if ($hasUserJoined)
                            <form action="/events/{{$event->id}}/subevents/join/{{$subEvent->id}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    Participar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach

            </div>
        </div>
    @endif
</div>

@endsection
