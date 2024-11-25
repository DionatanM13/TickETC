@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

<button class="btn btn-light col-md-6" onclick="toggleDashboardOwner()" id="toggle-owner">Meus eventos</button>
<button class="btn btn-dark col-md-6" onclick="toggleDashboardParticipant()" id="toggle-participant" style="background-color: #2e073f;">Participando</button>

<div id="event-owner-container" style="display: none;">

    <div class="col-md-10 offset-md-1 dashboard-title-container mb-4">
        <h1 class="text-center">Meus Eventos</h1>
    </div>

    <div class="col-md-10 offset-md-1">
        @if (count($events) > 0)
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Imagem</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Data</th>
                            <th scope="col">Hora</th>
                            <th scope="col">Local</th>
                            <th scope="col">Tamanho</th>
                            <th scope="col">Privado</th>
                            <th scope="col">Categorias</th>
                            <th scope="col">Participantes</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td scope="row">{{$loop->index + 1}}</td>
                                <td>
                                    @if ($event->image)
                                        <img src="{{ asset('img/events/'.$event->image) }}" alt="Imagem de Capa" class="img-thumbnail" style="max-width: 80px; height: auto;">
                                    @else
                                        <span class="text-muted">Sem Imagem</span>
                                    @endif
                                </td>
                                <td><a href="/events/{{$event->id}}" class="text-decoration-none" style="color: #49108b">{{$event->title}}</a></td>
                                <td>{{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->time)->format('H:i') }}</td>
                                <td>{{ $event->local }}</td>
                                <td>{{ $event->size }}</td>
                                <td>{{ $event->private ? 'Sim' : 'Não' }}</td>
                                <td>
                                    <span class="badge badge-light text-black">{{ implode(', ', $event->categories) }}</span>
                                </td>
                                <td>{{ count($event->users) }}</td>
                                <td class="d-flex justify-content-between w-100">
                                    <a href="dashboard/{{$event->id}}" class="btn btn-sm btn-outline-secondary"><ion-icon name="clipboard-outline"></ion-icon> Relatórios</a>
                                    <a href="/events/edit/{{$event->id}}" class="btn btn-sm btn-outline-primary"><ion-icon name="create-outline"></ion-icon> Editar</a>
                                    <form action="/events/{{$event->id}}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><ion-icon name="trash-outline"></ion-icon> Deletar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning">
                Você ainda não tem eventos, <a href="/events/create" class="alert-link">Criar Evento</a>
            </div>
        @endif
    </div>
</div>



<d<div id="event-participant-container">

<div class="col-md-10 offset-md-1 dashboard-title-container mb-4 text-center">
    <h1 class="text-center" >Eventos que estou Participando</h1>
</div>

<div class="col-md-10 offset-md-1 dashboard-events-container">
    @if (count($eventsAsParticipant) > 0)
        <div class="row">
            @foreach ($eventsAsParticipant as $event)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagem do Evento -->
                        <img 
                            src="{{ asset('img/events/' . $event->image) }}" 
                            class="card-img-top" 
                            alt="{{ $event->title }}" 
                            style="height: 180px; object-fit: cover;"
                        >
                        
                        <div class="card-body d-flex flex-column">
                            <!-- Título do Evento -->
                            <h5 class="card-title text-center text-primary">{{ $event->title }}</h5>

                            <!-- Botões de Ações -->
                            <div class="mt-auto">
                                <form action="/events/leave/{{ $event->id }}" method="post" class="mb-2 w-100">
                                    @csrf
                                    @method("delete")
                                    <button type="submit" class="btn btn-danger w-100">
                                        <ion-icon name="close-outline"></ion-icon> Sair do Evento
                                    </button>
                                </form>
                                <a href="/events/{{ $event->id }}" class="btn btn-info w-100">
                                    <ion-icon name="information-circle-outline"></ion-icon> Mostrar Informações
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning text-center">
            Você ainda não está participando de nenhum evento.
            <a href="/" class="alert-link">Veja todos os eventos</a>
        </div>
    @endif
</div>

</div>

@endsection