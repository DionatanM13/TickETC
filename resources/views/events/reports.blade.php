@extends('layouts.main')

@section('title', 'Relatórios de '. $event->title)

@section('content')

<h2>{{$event->title}}</h2>
<h3>Participantes</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Ticket</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($event->users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if ($user->pivot->ticket_id)
                        {{ $event->tickets->firstWhere('id', $user->pivot->ticket_id)->title }}
                    @else
                        Não selecionado
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"><strong>Total de Participantes:</strong></td>
            <td>{{ $event->users->count() }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Total de Vagas Disponíveis:</strong></td>
            <td>
                @php
                    $totalVagas = $event->tickets->sum('quantity');
                @endphp
                {{ $totalVagas }}
            </td>
        </tr>
    </tfoot>
</table>
@if (count($event->sub_events) > 0)
    <h3>Sub-Eventos</h3>
    @foreach ($event->sub_events as $subevent)
        <h4>{{ $subevent->title }}</h4>
        <p>{{ $subevent->description }}</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subevent->users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>Total de Participantes:</strong></td>
                    <td>{{ $subevent->users->count() }}</td>
                </tr>
                <tr>
                    <td><strong>Total de Vagas Disponíveis:</strong></td>
                    <td>
                        @php
                            $vagasOcupadasSub = $subevent->users->count();
                            $vagasDisponiveis = $subevent->size - $vagasOcupadasSub;
                        @endphp
                        {{ $vagasDisponiveis }}
                    </td>
                </tr>
            </tfoot>
        </table>
    @endforeach
@endif


<h3>Tickets</h3>
<table class="table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Quantidade Disponível</th>
            <th>Quantidade Vendida</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($event->tickets as $ticket)
            <tr>
                <td>{{$ticket->title}}</td>
                <td>{{$ticket->description}}</td>
                <td>R$ {{number_format($ticket->price, 2, ',', '.')}}</td>
                <td>{{$ticket->quantity}}</td>
                <td>{{$ticket->sold_count}}</td>
                <td>R$ {{number_format($ticket->revenue, 2, ',', '.')}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection