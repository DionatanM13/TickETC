@extends('layouts.main')

@section('title', 'Criar Evento')

@section('content')

<div id="event-create-container" class="col-md-6 offset-md-3">
    <h1>Crie seu Evento</h1>
    <form action="/events" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="image">Imagem de Capa:</label>
            <input type="file" name="image" id="image" class="form-control-file">
        </div>
        <div class="form-group">
            <label for="title">Evento:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Nome o evento">
        </div>
        <div class="d-flex col-md-12">
            <div class="form-group col-md-2 offset-md-2">
                <label for="date">Data do evento:</label>
                <input type="date" class="form-control" id="date" name="date">
            </div>
            <div class="form-group col-md-2 offset-md-3">
                <label for="time">Hora do evento:</label>
                <input type="time" class="form-control" id="time" name="time">
            </div>
        </div>
        
        <div class="form-group">
            <label for="days">O evento dura mais de um dia?</label>
            <select name="days" id="days" class="form-control">
                <option value="0">Não</option>
                <option value="1">Sim</option>
            </select>
        </div>
        <div class="form-group col-md-4" id="finalDate" style="display: none;">
            <label for="finalDate">Data final do evento:</label>
            <input type="date" class="form-control" id="finalDate" name="finalDate">
        </div>
        <div class="form-group">
            <label for="city">Cidade:</label>
            <input type="text" class="form-control" id="city" name="city" placeholder="Cidade do evento">
        </div>
        <div class="form-group">
            <label for="local">Local do Evento:</label>
            <input type="text" class="form-control" id="local" name="local" placeholder="Local do evento">
        </div>
        <div class="form-group col-md-4">
            <label for="size">Tamanho do Evento: </label>
            <select name="size" id="size" class="form-control">
                <option value="pequeno">Pequeno: 200 Participantes</option>
                <option value="medio" selected>Médio: 600 Participantes</option>
                <option value="grande">Grande: +1000 Participantes</option>
            </select>
        </div>
        <div class="form-group">
            <label for="private">Evento Privado:</label>
            <select name="private" id="private" class="form-control">
                <option value="0">Não</option>
                <option value="1">Sim</option>
            </select>
        </div>
        
        <div class="form-group" id="campoDominio" style="display: none;">
            <label for="dominio">Email permitido:</label>
            @<input type="text" class="form-control" id="dominio" name="dominio" placeholder="dominio">
        </div>
        
        <div class="form-group">
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" class="form-control" placeholder="Descrição do evento"></textarea>
        </div>
        <div class="form-group">
            <label for="categories">Categorias:</label>
            <div class="form-group">
                <input type="checkbox" name="categories[]" value="Show">Show
            </div>
            <div class="form-group">
                <input type="checkbox" name="categories[]" value="Educativo">Educativo
            </div>
            <div class="form-group">
                <input type="checkbox" name="categories[]" value="Esportivo">Esportivo
            </div>
            <div class="form-group">
                <input type="checkbox" name="categories[]" value="Feira">Feira
            </div>
            <div class="form-group">
                <input type="checkbox" name="categories[]" value="Palestra">Palestra
            </div>
            <div class="form-group">
                <input type="checkbox" name="categories[]" value="Reunião">Reunião
            </div>
            
        </div>
        <input type="submit" class="btn btn-success" value="Criar Evento">
    </form>
</div>

<script src="/js/scripts.js"></script>
@endsection