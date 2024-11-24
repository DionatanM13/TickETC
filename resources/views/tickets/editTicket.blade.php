@extends("layouts.main")

@section('title', 'Editar Ticket: '. $ticket->title)

@section('content')

<div id="event-create-container" class="col-md-8 offset-md-2">
    <h1 class="text-center mb-4">Editando seu Ticket/Ingresso</h1>
    <form action="/events/{{$event->id}}/tickets/update/{{$ticket->id}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="title">Título:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Título do ingresso" required value="{{$ticket->title}}">
        </div>

        <div class="form-group mb-3">
            <label for="batch">Lote:</label>
            <input type="number" class="form-control" id="batch" name="batch" min="1" required value="{{$ticket->batch}}">
        </div>

        <div class="form-group mb-3">
            <label for="price">Valor:</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0.01" required value="{{$ticket->price}}">
        </div>

        <div class="form-group mb-3">
            <label for="quantity">Quantidade disponível:</label>
            <input type="number" class="form-control" name="quantity" id="quantity" min="1" required value="{{$ticket->quantity}}">
        </div>

        <div class="form-group mb-3">
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" class="form-control" placeholder="Descrição do ingresso" rows="4" required>{{$ticket->description}}</textarea>
        </div>

        <input type="submit" class="btn btn-success w-100" value="Editar Ticket">
    </form>
</div>

@endsection
