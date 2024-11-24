@extends("layouts.main")

@section('title', 'Criar SubEvento')

@section('content')


<div id="event-create-container" class="col-md-6 offset-md-3">
    <h1>Crie seu SubEvento</h1>
    <form action="/events/{{$event->id}}/subevents" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Subvento:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Nome do subevento">
        </div>
        <div class="form-group">
            <label for="date">Data do subevento:</label>
            <input type="date" class="form-control" id="date" name="date">
        </div>
        <div class="form-group">
            <label for="local">Local do Subevento:</label>
            <input type="text" class="form-control" id="local" name="local" placeholder="Local do subevento">
        </div>
        <div class="form-group">
            <label for="size">Tamanho do Evento: </label>
            <select name="size" id="size" class="form-control">
                <option value=200>Pequeno: 200</option>
                <option value=600 selected>Médio: 600</option>
                <option value=1000>Grande: +1000</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" class="form-control" placeholder="Descrição do subevento"></textarea>
        </div>

        <input type="submit" class="btn btn-success" value="Criar Subevento">
    </form>
</div>

@if (count($subEvents) > 0)
    <!-- Seção para exibir subeventos -->
    <div id="subevents-container">
        <h3>Subeventos já criados:</h3>
        <ul>
            @foreach($subEvents as $subEvent)
                <li>
                    <strong>{{ $subEvent->title }}</strong> - {{ $subEvent->description }}
                </li>
            @endforeach
        </ul>
    </div>
@endif
@endsection