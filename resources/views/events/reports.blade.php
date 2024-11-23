@extends('layouts.main')

@section('title', 'Relatórios de '. $event->title)

@section('content')

<h2>{{$event->title}}</h2>
<h3>Participantes</h3>
@foreach ($event->users as $user)
    <p>
        Nome: {{$user->name}} - Email: {{$user->email}} - 
        Ticket: 
        @if ($user->pivot->ticket_id)
            {{$event->tickets->firstWhere('id', $user->pivot->ticket_id)->title}}
        @else
            Não selecionado
        @endif
    </p>
@endforeach

<h3>Sub-Eventos</h3>
@foreach ($event->sub_events as $subevent)
    <p>
        {{$subevent->title}} - {{$subevent->description}} - Participantes: {{$subevent->users->count()}}
    </p>
@endforeach

<h3>Tickets</h3>
<table class="table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Quantidade Disponível</th>
            <th>Quantidade Vendida</th>
            <th>Total Ganho</th>
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