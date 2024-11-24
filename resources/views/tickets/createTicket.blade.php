@extends("layouts.main")

@section('title', 'Criar Ticket/Ingresso')

@section('content')

<div id="event-create-container" class="container-fluid col-md-8">

    <div class="d-flex justify-content-start mb-4">
        <a href="/events/{{$event->id}}" class="btn btn-light p-2" style="width: 150px;">
            <ion-icon name="arrow-back-outline"></ion-icon> Voltar
        </a>
    </div>

    <h1 class="text-center mb-4">Crie seu Ticket/Ingresso</h1>
    <form action="/events/{{$event->id}}/tickets" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-3">
            <label for="title">Título:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Título do ingresso" required>
        </div>

        <div class="form-group mb-3">
            <label for="batch">Lote:</label>
            <input type="number" class="form-control" id="batch" name="batch" min="1" required>
        </div>

        <div class="form-group mb-3">
            <label for="price">Valor:</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0.01" required>
        </div>

        <div class="form-group mb-3">
            <label for="quantity">Quantidade disponível:</label>
            <input type="number" class="form-control" name="quantity" id="quantity" min="1" required>
        </div>

        <div class="form-group mb-3">
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" class="form-control" placeholder="Descrição do ingresso" rows="4" required></textarea>
        </div>

        <input type="submit" class="btn btn-success w-100" value="Criar Ticket">
    </form>
</div>

@if (count($tickets) > 0)
    <!-- Seção para exibir os tickets criados -->
    <div id="tickets-container" class="container-fluid mt-5">
        <h3>Ingressos já criados:</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Lote</th>
                        <th>Valor</th>
                        <th>Quantidade Disponível</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->title }}</td>
                            <td>{{ $ticket->batch }}</td>
                            <td>R${{ number_format($ticket->price, 2, ',', '.') }}</td>
                            <td>{{ $ticket->quantity }}</td>
                            <td>{{ $ticket->description }}</td>
                            <td>
                                <a href="/events/{{$event->id}}/tickets/{{$ticket->id}}/edit" class="btn btn-warning btn-sm">Editar</a>
                                <form action="/events/{{$event->id}}/tickets/{{$ticket->id}}" method="POST" style="display:inline;">
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
