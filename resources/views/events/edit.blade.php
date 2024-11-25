@extends('layouts.main')

@section('title', 'Editando: '.$event->title)

@section('content')
<div id="event-create-container" class="col-md-8 offset-md-2 mt-5">
    <h1>Editando: {{$event->title}}</h1>
    <form action="/events/update/{{$event->id}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Imagem de Capa -->
        <div class="form-group mb-3">
            <label for="image">Imagem de Capa:</label>
            <input type="file" name="image" id="image" class="form-control-file">
            @if($event->image)
                <img src="/img/events/{{$event->image}}" alt="{{$event->title}}" class="img-preview mt-2" style="max-width: 100%; height: auto;">
            @endif
        </div>

        <!-- Nome do Evento -->
        <div class="form-group mb-3">
            <label for="title">Nome do Evento:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Nome do evento" value="{{$event->title}}" required>
        </div>

        <!-- Data e Hora -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="date">Data do Evento:</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d', strtotime($event->date)) }}" required>
            </div>
            <div class="col-md-6">
                <label for="time">Hora do Evento:</label>
                <input type="time" class="form-control" id="time" name="time" value="{{$event->time}}" required>
            </div>
        </div>

        <!-- Evento com mais de um dia -->
        <div class="form-group mb-3">
            <label for="days">O evento dura mais de um dia?</label>
            <select name="days" id="days" class="form-control">
                <option value="0">Não</option>
                <option value="1" {{$event->finalDate ? "selected" : ""}}>Sim</option>
            </select>
        </div>

        <!-- Data Final -->
        <div class="form-group mb-3" id="finalDate" >
            <label for="finalDate">Data Final do Evento:</label>
            <input type="date" class="form-control" id="finalDate" name="finalDate" value="{{$event->finalDate}}">
        </div>

        <!-- Cidade e Local -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="city">Cidade:</label>
                <input type="text" class="form-control" id="city" name="city" value="{{$event->city}}" required>
            </div>
            <div class="col-md-6">
                <label for="local">Local do Evento:</label>
                <input type="text" class="form-control" id="local" name="local" value="{{$event->local}}" required>
            </div>
        </div>

        <!-- Tamanho do Evento -->
        <div class="form-group mb-3">
            <label for="size">Tamanho do Evento:</label>
            <select name="size" id="size" class="form-control">
                <option value=200 {{$event->size == 200 ? "selected" : ""}}>Pequeno: até 200 Participantes</option>
                <option value=600 {{$event->size == 600 ? "selected" : ""}}>Médio: até 600 Participantes</option>
                <option value=1000 {{$event->size == 1000 ? "selected" : ""}}>Grande: +1000 Participantes</option>
            </select>
        </div>

        <!-- Evento Privado -->
        <div class="form-group mb-3">
            <label for="private">Evento Privado:</label>
            <select name="private" id="private" class="form-control">
                <option value="0" {{$event->private == 0 ? "selected" : ""}}>Não</option>
                <option value="1" {{$event->private == 1 ? "selected" : ""}}>Sim</option>
            </select>
        </div>

        <!-- Domínio do Email -->
        <div class="form-group mb-3" id="campoDominio" style="display: none;">
            <label for="dominio">Email Permitido:</label>
            <div class="input-group">
                <span class="input-group-text" style="height:32px;">@</span>
                <input type="text" class="form-control" id="dominio" name="dominio" placeholder="dominio.com" value="{{$event->dominio}}">
            </div>
        </div>

        <!-- Descrição -->
        <div class="form-group mb-3">
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{$event->description}}</textarea>
        </div>

        <!-- Categorias -->
        <div class="form-group mb-3">
            <label for="categories">Categorias:</label>
            <div class="row">
                @foreach (['Show', 'Educativo', 'Esportivo', 'Feira', 'Palestra', 'Reunião'] as $category)
                    <div class="col-md-4">
                        <input type="checkbox" name="categories[]" value="{{ $category }}" 
                        {{ in_array($category, $event->categories) ? "checked" : "" }}> {{ $category }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Botão de Enviar -->
        <div class="d-flex justify-content-center">
            <input type="submit" class="btn btn-primary btn-lg" value="Editar Evento">
        </div>
    </form>

    
</div>
<script src="/js/scripts.js"></script>
@endsection
