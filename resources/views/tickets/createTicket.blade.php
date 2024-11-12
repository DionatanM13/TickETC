@extends("layouts.main")

@section('title', 'Criar Ticket/Ingresso')

@section('content')


<div id="event-create-container" class="col-md-6 offset-md-3">
    <h1>Crie seu Ticket/Ingresso</h1>
    <form action="/events/{{$event->id}}/tickets" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">titulo:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Titulo do ingresso">
        </div>
        <div class="form-group">
            <label for="batch">Lote:</label>
            <input type="number" class="form-control" id="batch" name="batch" min="1">
        </div>
        <div class="form-group">
            <label for="price">Valor:</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0.01">
        </div>
        <div class="form-group">
            <label for="quantity">Quantidade disponível: </label>
            <input type="number" class="form-control" name="quantity" id="quantity">
        </div>
        
        <div class="form-group">
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" class="form-control" placeholder="Descrição do ingresso"></textarea>
        </div>

        <input type="submit" class="btn btn-primary" value="Criar Ticket">
    </form>
</div>

@if (count($tickets) > 0)
    <!-- Seção para exibir subeventos -->
    <div id="subevents-container">
        <h3>Ingressos já criados:</h3>
        <ul>
            @foreach($tickets as $ticket)
                <li>
                    <strong>{{ $ticket->title }}</strong> - {{ $ticket->description }}
                </li>
            @endforeach
        </ul>
    </div>
@endif
@endsection