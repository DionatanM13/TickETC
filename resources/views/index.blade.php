@extends('layouts.main')

@section('title', 'Página Inicial')

@section('content')

<div id="search-container" class="col-md-12">
    <h1>Busque um Evento</h1>
    <form action="/" method="GET">
        <input type="text" id="search" name="search" class="form-control" placeholder="Procurar">
    </form>
</div>

<div id="events-container" class="col-md-12">
    @if ($search)
        <h2>Eventos com: {{$search}}</h2>
    @else
        <h2>Próximos Eventos</h2>
        <p class="subtitle">Veja os eventos dos próximos dias</p>
    @endif
    <div id="cards-container" class="row">
        @foreach ($events as $event)
        <div class="card col-md-3 ">
            <img src="/img/events/{{$event->image}}" alt="{{$event->title}}">
            <div class="card-body">
                <p class="card-date">{{date('d/m/Y', strtotime($event->date))}} 
                    @if ($event->finalDate)
                        - {{date('d/m/Y', strtotime($event->finalDate))}}
                    @endif
                </p>
                
                <h5 class="card-title">{{$event->title}}</h5>
                <p class="card-city">{{$event->city}}</p>
                <a href="/events/{{$event->id}}" class="btn btn-dark btn-category" role="button">Saber mais</a>
            </div>
        </div>
        @endforeach

        @if (count($events) == 0 && $search)
            <p>Não foi possível encontrar eventos com {{$search}}! <a href="/">Ver todos</a></p>
        
        @elseif(count($events) == 0)
            <p>Não há eventos disponíveis</p>  
        @endif
    </div>
</div>

<div class="container-fluid my-5">
    <h2 class="text-center mb-4">Categorias</h2>
    <div class="row text-center">
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <a href="" class="d-block">
                <img src="/img/categorias/show.png" alt="Show" class="img-fluid rounded-circle shadow categoria-img">
                <p class="mt-2 categoria-title">Show</p>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <a href="" class="d-block">
                <img src="/img/categorias/educacional.png" alt="Educacional" class="img-fluid rounded-circle shadow categoria-img">
                <p class="mt-2 categoria-title">Educacional</p>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <a href="" class="d-block">
                <img src="/img/categorias/esportes.png" alt="Esportivo" class="img-fluid rounded-circle shadow categoria-img">
                <p class="mt-2 categoria-title">Esportivo</p>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <a href="" class="d-block">
                <img src="/img/categorias/feira.png" alt="Feira" class="img-fluid rounded-circle shadow categoria-img">
                <p class="mt-2 categoria-title">Feira</p>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <a href="" class="d-block">
                <img src="/img/categorias/feira.png" alt="Feira" class="img-fluid rounded-circle shadow categoria-img">
                <p class="mt-2 categoria-title">Feira</p>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <a href="" class="d-block">
                <img src="/img/categorias/feira.png" alt="Feira" class="img-fluid rounded-circle shadow categoria-img">
                <p class="mt-2 categoria-title">Feira</p>
            </a>
        </div>
        <!-- Adicione mais categorias conforme necessário -->
    </div>
</div>



@endsection