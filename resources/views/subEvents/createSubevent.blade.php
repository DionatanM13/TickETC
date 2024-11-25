@extends("layouts.main")

@section('title', 'Criar SubEvento')

@section('content')

<div id="event-create-container" class="container-fluid col-md-8">
    <!-- Botão de Voltar posicionado acima do título -->
    <div class="d-flex justify-content-start mb-4">
        <a href="/events/{{$event->id}}" class="btn btn-light p-2" style="width: 150px;">
            <ion-icon name="arrow-back-outline"></ion-icon> Voltar
        </a>
    </div>
    
    <h1 class="text-center mb-4">Crie seu SubEvento</h1>    

    @if ($errors->any())
        <div class="alert alert-danger">
            <h4>Ocorreram os seguintes erros:</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulário responsivo -->
    <form action="/events/{{$event->id}}/subevents" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
            <label for="title">SubEvento:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Nome do subevento" required>
        </div>
        
        <div class="row mb-3">
            <!-- Responsividade para dispositivos móveis (col-md-6 divide os campos em duas colunas) -->
            <div class="form-group mb-3 col-md-4">
                <label for="date">Data do SubEvento:</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            
            <div class="form-group mb-3 col-md-4">
                <label for="time">Hora do Sub-evento:</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>

            <div class="form-group mb-3 col-md-4">
                <label for="finalTime">Término do Sub-evento:</label>
                <input type="time" class="form-control" id="finalTime" name="finalTime" required>
            </div>
        </div>
        
        <div class="form-group mb-3">
            <label for="local">Local do SubEvento:</label>
            <input type="text" class="form-control" id="local" name="local" placeholder="Local do subevento" required>
        </div>

        <div class="form-group mb-3">
            <label for="size">Capacidade do Evento:</label>
            <input type="number" name="size" id="size" class="form-control" required>
        </div>
        
        <div class="form-group mb-3">
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" class="form-control" placeholder="Descrição do subevento" rows="4" required></textarea>
        </div>

        <input type="submit" class="btn btn-success w-100" value="Criar SubEvento">
    </form>
</div>

@if (count($subEvents) > 0)
    <!-- Seção para exibir os subeventos criados -->
    <div id="subevents-container" class="container-fluid mt-5">
        <h3>Subeventos Já Criados:</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Local</th>
                        <th>Capacidade</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subEvents as $subEvent)
                        <tr>
                            <td>{{ $subEvent->title }}</td>
                            <td>{{ date('d/m/Y', strtotime($subEvent->date)) }}</td>
                            <td>{{ date('H:i', strtotime($subEvent->time)) }}</td>
                            <td>{{ $subEvent->local }}</td>
                            <td>{{ $subEvent->size }}</td>
                            <td>{{ $subEvent->description }}</td>
                            <td>
                                <a href="/events/{{$event->id}}/subevents/{{$subEvent->id}}/edit" class="btn btn-warning btn-sm">Editar</a>
                                <form action="/events/{{$event->id}}/subevents/{{$subEvent->id}}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection
