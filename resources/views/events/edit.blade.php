@extends('layouts.main')

@section('title', 'Editando: '.$event->title)

@section('content')
    <div id="event-create-container" class="col-md-6 offset-md-3">
        <h1>Editando: {{$event->title}}</h1>
        <form action="/events/update/{{$event->id}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="image">Imagem de Capa:</label>
                <input type="file" name="image" id="image" class="form-control-file">
                <img src="/img/events/{{$event->image}}" alt="{{$event->title}}" class="img-preview">
            </div>
            <div class="form-group">
                <label for="title">Evento:</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Nome o evento" value="{{$event->title}}">
            </div>
            <div class="form-group">
                <label for="date">Data do evento:</label>
                <input type="date" class="form-control" id="date" name="date" value="{{date('Y-m-d', strtotime($event->date));}}">
            </div>
            <div class="form-group">
                <label for="time">Hora do evento:</label>
                <input type="time" class="form-control" id="time" name="time" value="{{$event->time}}">
            </div>
            <div class="form-group">
                <label for="days">O evento dura mais de um dia?</label>
                <select name="days" id="days" class="form-control">
                    <option value="0">Não</option>
                    <option value="1" {{$event->finalDate ? "selected='selected": ''}}>Sim</option>
                </select>
            </div>
            <div class="form-group col-md-4" id="finalDate" {{$event->finalDate != null ? "style='display: none;'": "style='display: block;'"}}>
                <label for="finalDate">Data final do evento:</label>
                <input type="date" class="form-control" id="finalDate" name="finalDate" value="{{$event->finalDate}}">
            </div>
            <div class="form-group">
                <label for="city">Cidade:</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Local do evento" value="{{$event->city}}">
            </div>
            <div class="form-group">
                <label for="local">Local do Evento:</label>
                <input type="text" class="form-control" id="local" name="local" placeholder="Local do evento" value="{{$event->local}}">
            </div>
            <div class="form-group">
                <label for="private">Evento Privado:</label>
                <select name="private" id="private" class="form-control">
                    <option value="0">Não</option>
                    <option value="1" {{$event->private == 1 ? "selected='selected": ''}}>Sim</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Descrição:</label>
                <textarea name="description" id="description" class="form-control" placeholder="Descrição do evento">{{$event->description}}</textarea>
            </div>
            <div class="form-group">
                <label for="categories">Categorias:</label>
                <div class="form-group">
                    <input type="checkbox" name="categories[]" value="Show" {{(in_array("Show", $event->categories)) ? "checked='checked' "  :  ' '  }}>Show
                </div>
                <div class="form-group">
                    <input type="checkbox" name="categories[]" value="Educativo" {{(in_array("Educativo", $event->categories)) ? "checked='checked' "  :  ' '  }}>Educativo
                </div>
                <div class="form-group">
                    <input type="checkbox" name="categories[]" value="Esportivo" {{(in_array("Esportivo", $event->categories)) ? "checked='checked' "  :  ' '  }}>Esportivo
                </div>
                <div class="form-group">
                    <input type="checkbox" name="categories[]" value="Feira" {{(in_array("Feira", $event->categories)) ? "checked='checked' "  :  ' '  }}>Feira
                </div>
                <div class="form-group">
                    <input type="checkbox" name="categories[]" value="Reunião" {{(in_array("Reunião", $event->categories)) ? "checked='checked' "  :  ' '  }}>Reunião
                </div>
            </div>
            <input type="submit" class="btn btn-primary" value="Editar Evento">
        </form>
    </div>

    
<script src="/js/scripts.js"></script>
@endsection