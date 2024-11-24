@extends("layouts.main")

@section('title', 'Editando SubEvento: '. $subevent->title)

@section('content')

<div id="event-create-container" class="container-fluid col-md-8">

    <div class="d-flex justify-content-start mb-4">
        <a href="/events/{{$event->id}}" class="btn btn-light p-2" style="width: 150px;">
            <ion-icon name="arrow-back-outline"></ion-icon> Voltar
        </a>
    </div>
    <h1 class="text-center mb-4">Editar SubEvento</h1>
    
    <form action="/events/{{$event->id}}/subevents/update/{{$subevent->id}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="title">SubEvento:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Nome do subevento" required value="{{$subevent->title}}">
        </div>
        
        <div class="row mb-3">
            <div class="form-group mb-3 col-md-6">
                <label for="date">Data do SubEvento:</label>
                <input type="date" class="form-control" id="date" name="date" required value="{{$subevent->date}}">
            </div>
            
            <div class="form-group mb-3 col-md-6">
                <label for="time">Hora do SubEvento:</label>
                <input type="time" class="form-control" id="time" name="time" required value="{{$subevent->time}}">
            </div>
        </div>
        
        
        <div class="form-group mb-3">
            <label for="local">Local do SubEvento:</label>
            <input type="text" class="form-control" id="local" name="local" placeholder="Local do subevento" required value="{{$subevent->local}}">
        </div>

        <div class="form-group mb-3">
            <label for="size">Capacidade do Evento:</label>
            <input type="number" name="size" id="size" class="form-control" required value="{{$subevent->size}}">
        </div>
        
        <div class="form-group mb-3">
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" class="form-control" placeholder="Descrição do subevento" rows="4" required>{{$subevent->description}}</textarea>
        </div>

        <input type="submit" class="btn btn-success w-100" value="Editar SubEvento">
    </form>
</div>

@endsection
