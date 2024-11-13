@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

<button class="btn btn-light col-md-6" onclick="toggleDashboardOwner()" id="toggle-owner">Meus eventos</button>
<button class="btn btn-dark col-md-6" onclick="toggleDashboardParticipant()" id="toggle-participant">Participando</button>

<div id="event-owner-container" style="display: none;">

    <div class="col-md-10 offset-md-1 dashboard-title-container">
        <h1>Meus Eventos</h1>
    </div>

    <div class="col-md-10 offset-md-1 dashboard-events-container">
        @if (count($events) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Participantes</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td scope="row">{{$loop->index + 1}}</td>
                            <td><a href="/events/{{$event->id}}"> {{$event->title}}</a></td>
                            <td>{{count($event->users)}}</td>
                            <td>
                                <a href="dashboard/{{$event->id}}" class="btn btn-secondary"> <ion-icon name="clipboard-outline"></ion-icon> Relatórios</a>
                                <a href="/events/edit/{{$event->id}}" class="btn btn-info edit-btn"> <ion-icon name="create-outline"></ion-icon> Editar</a>
                                <form action="/events/{{$event->id}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger delete-btn"> <ion-icon name="trash-outline"></ion-icon> Deletar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Você ainda não tem eventos, <a href="/events/create">Criar Evento</a></p>
        @endif
    </div>
</div>

<div id="event-participant-container">


    <div class="col-md-10 offset-md-1 dashboard-title-container">
        <h1>Eventos que estou Participando</h1>
    </div>
    <div class="col-md-10 offset-md-1 dashboard-events-container">
        @if (count($eventsAsParticipant) > 0)
            
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Participantes</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($eventsAsParticipant as $event)
                        <tr>
                            <td scope="row">{{$loop->index + 1}}</td>
                            <td><a href="/events/{{$event->id}}"> {{$event->title}}</a></td>
                            <td>{{count($event->users)}}</td>
                            <td>
                                <form action="/events/leave/{{$event->id}}" method="post">
                                    @csrf
                                    @method("delete")
                                    <button type="submit" class="btn btn-danger delete-btn">
                                        <ion-icon name="trash-outline"></ion-icon>
                                        Sair do Evento
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @else
            <p>Você ainda não está participando em nenhum evento <a href="/">Veja todos os eventos</a></p>
        @endif
    </div>
</div>
@endsection