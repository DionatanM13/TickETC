@extends('layouts.main')

@section('title', 'Relatórios de '. $event->title)

@section('content')

<h2>{{$event->title}}</h2>
<h3>Participantes</h3>
@foreach ($event->users as $user)
    <p>{{$user->name}} - {{$user->email}} - {{$user->subEventRegistration}}</p>
@endforeach

<h3>Sub-Eventos</h3>
@foreach ($event->sub_events as $subevent)
    <p>{{$subevent->title}} - {{$subevent->description}} - {{$subevent->users}}</p>
@endforeach

<h3>Tickets</h3>
@foreach ($event->tickets as $ticket)
    <p>{{$ticket->title}} - {{$ticket->description}} {{$ticket}}</p>
@endforeach






@endsection