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
    @elseif($category)
        <h2>Eventos com a categoria {{$category}}</h2>
    @else
        <h2>Próximos Eventos</h2>
        <p class="subtitle">Veja os eventos dos próximos dias</p>
    @endif
    <div id="cards-container" class="row">
        <div class="col-12">
            <div id="event-scroll" class="d-flex overflow-auto">
                @foreach ($events->take(10) as $event)  <!-- Limita para 10 eventos -->
                    <div class="card fixed-height" style="flex: 0 0 auto; width: 400px; margin-right: 15px; height: 500px;"> <!-- Definido um width fixo para controlar o tamanho -->
                        <img src="/img/events/{{$event->image}}" alt="{{$event->title}}" class="img-fluid">
                        <div class="card-body">
                            <p class="card-date text-muted">{{date('d/m/Y', strtotime($event->date))}} 
                                @if ($event->finalDate)
                                    - {{date('d/m/Y', strtotime($event->finalDate))}}
                                @endif
                            </p>

                            <h5 class="card-title">{{$event->title}}</h5>
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @foreach ($event->categories as $category)
                                    <span class="badge bg-secondary text-light">{{$category}}</span>
                                @endforeach
                            </div>
                            <p class="card-city">{{$event->city}}</p>
                            <a href="/events/{{$event->id}}" class="btn btn-dark btn-category" role="button">Saber mais</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        @if (count($events) == 0 && $search)
            <p>Não foi possível encontrar eventos com {{$search}}! <a href="/">Ver todos</a></p>
        
        @elseif (count($events) == 0 && $category)
            <p>Não foi possível encontrar eventos com {{$category}}! <a href="/">Ver todos</a></p>
        @elseif(count($events) == 0)
            <p>Não há eventos disponíveis</p>  
        @endif
    </div>
</div>

<div class="container-fluid my-5 mx-auto">
    <h2 class="text-center mb-4">Categorias</h2>
    <div class="row text-around justify-content-md-center">

        <div class="col-6 col-md-4 col-lg-2 mx-auto">
            <form action="/" method="GET">
                <!-- O valor da categoria é passado para a busca -->
                <input type="hidden" id="search-category" name="search-category" value="Show">
                <button type="submit" class="d-block" style="background: none; border: none;">
                    <img src="/img/categorias/show.png" alt="Show" class="img-fluid rounded-circle shadow categoria-img">
                    <p class="mt-2 categoria-title">Show</p> 
                </button>
            </form>
        </div>

        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <form action="/" method="GET">
                <input type="hidden" id="search-category" name="search-category" value="Educativo">
                <button type="submit" class="d-block" style="background: none; border: none;">
                    <img src="/img/categorias/educacional.png" alt="Educativo" class="img-fluid rounded-circle shadow categoria-img">
                    <p class="mt-2 categoria-title">Educacional</p>
                </button>
            </form>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <form action="/" method="GET">
                <!-- O valor da categoria é passado para a busca -->
                <input type="hidden" id="search-category" name="search-category" value="Esportivo">
                <button type="submit" class="d-block" style="background: none; border: none;">
                    <img src="/img/categorias/esportes.png" alt="Esportivo" class="img-fluid rounded-circle shadow categoria-img">
                    <p class="mt-2 categoria-title">Esportivo</p>
                </button>  
            </form>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <form action="/" method="GET">
                <input type="hidden" id="search-category" name="search-category" value="Feira">
                    <button type="submit" class="d-block" style="background: none; border: none;">
                        <img src="/img/categorias/feira.png" alt="Feira" class="img-fluid rounded-circle shadow categoria-img">
                        <p class="mt-2 categoria-title">Feira</p>    
                    </button>
            </form>
        </div>

        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <form action="/" method="GET">
                <input type="hidden" id="search-category" name="search-category" value="Palestra">
                    <button type="submit" class="d-block" style="background: none; border: none;">
                        <img src="/img/categorias/Palestra.png" alt="Feira" class="img-fluid rounded-circle shadow categoria-img">
                        <p class="mt-2 categoria-title">Palestra</p>    
                    </button>
            </form>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <form action="/" method="GET">
                <input type="hidden" id="search-category" name="search-category" value="Reunião">
                    <button type="submit" class="d-block" style="background: none; border: none;">
                        <img src="/img/categorias/Reunião.png" alt="Feira" class="img-fluid rounded-circle shadow categoria-img">
                        <p class="mt-2 categoria-title">Reunião</p>    
                    </button>
            </form>
        </div>

    </div>
</div>

<div class="container-fluid  my-5 mx-auto">
    <div class="row d-flex col-sm-12 justify-content-center" id="events-container">
        @foreach ($events->take(9) as $event) <!-- Limita inicialmente para 9 eventos -->
            <div class="col-sm-12 col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                <div class="card w-100 shadow-sm">
                    <img src="/img/events/{{$event->image}}" alt="{{$event->title}}" class="img-fluid card-img-top">
                    <div class="card-body">
                        <p class="card-date text-muted">
                            {{date('d/m/Y', strtotime($event->date))}} 
                            @if ($event->finalDate)
                                - {{date('d/m/Y', strtotime($event->finalDate))}}
                            @endif
                        </p>
                        <h5 class="card-title">{{$event->title}}</h5>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @foreach ($event->categories as $category)
                                <span class="badge bg-secondary text-light">{{$category}}</span>
                            @endforeach
                        </div>
                        <p class="card-city">{{$event->city}}</p>
                        <a href="/events/{{$event->id}}" class="btn btn-dark w-100" role="button">Saber mais</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Botão Mostrar Mais -->
    <div class="text-center mt-4">
        <button id="load-more-btn" class="btn btn-dark">Mostrar mais</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let skip = 9;
    const loadMoreBtn = document.getElementById('load-more-btn');
    const eventsContainer = document.querySelector('.row.d-flex'); // O contêiner onde os eventos serão adicionados

    loadMoreBtn.addEventListener('click', function () {
        fetch(`/events/load-more?skip=${skip}&limit=9`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor');
                }
                return response.json();
            })
            .then(events => {
                events.forEach(event => {
                    const eventHtml = `
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                            <div class="card w-100 shadow-sm">
                                <img src="/img/events/${event.image}" alt="${event.title}" class="img-fluid card-img-top">
                                <div class="card-body">
                                    <p class="card-date text-muted">
                                        ${event.date} 
                                        ${event.finalDate ? `- ${event.finalDate}` : ''}
                                    </p>
                                    <h5 class="card-title">${event.title}</h5>
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        ${event.categories.map(category => `<span class="badge bg-secondary text-light">${category}</span>`).join('')}
                                    </div>
                                    <p class="card-city text-muted">${event.city}</p>
                                    <a href="/events/${event.id}" class="btn btn-dark w-100" role="button">Saber mais</a>
                                </div>
                            </div>
                        </div>
                    `;
                    eventsContainer.innerHTML += eventHtml;
                });

                // Incrementa o valor de skip para a próxima chamada
                skip += 9;
            })
            .catch(error => {
                console.error('Erro ao carregar mais eventos:', error);
            });
    });
});

</script>

@endsection