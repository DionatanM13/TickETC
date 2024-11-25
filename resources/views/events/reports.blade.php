@extends('layouts.main')

@section('title', 'Relatórios de '. $event->title)

@section('content')

<div class="container mt-4">
    <!-- Título do Evento -->
    <h2 class="text-center" style="color: #2e073f;">{{ $event->title }}</h2>

    <!-- Participantes -->
    <div class="mt-5">
        <div class="d-flex justify-content-around align-items-center p-2" style="background-color: #7e30e1; border-radius: 12px;">
            <h3 class="text-light col-md-2">Participantes</h3>
            <button class="btn btn-outline-light btn-sm" onclick="toggleTable('participants-table')">Mostrar Informações</button>
        </div>
        <div id="participants-table" class="table-responsive mt-3" style="display: none;">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
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
                        <td>{{ $event->tickets->sum('quantity') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Sub-Eventos -->
    @if (count($event->sub_events) > 0)
        <div class="mt-5">
            <div class="d-flex justify-content-around align-items-center p-2" style="background-color: #7e30e1; border-radius: 12px;">
                <h3 class="text-light col-md-2">Sub-Eventos</h3>
                <button class="btn btn-outline-light btn-sm" onclick="toggleTable('subevents-table')">Mostrar Informações</button>
            </div>
            <div id="subevents-table" class="table-responsive mt-3" style="display: none;">
                @foreach ($event->sub_events as $subevent)
                    <div class="mb-4">
                        <h4 class="text-primary">{{ $subevent->title }}</h4>
                        <p>{{ $subevent->description }}</p>

                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
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
                                    <td>{{ $subevent->size - $subevent->users->count() }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Tickets -->
    <div class="mt-5">
        <div class="d-flex justify-content-around align-items-center p-2" style="background-color: #7e30e1; border-radius: 12px;">
            <h3 class="text-light col-md-2">Tickets</h3>
            <button class="btn btn-outline-light btn-sm" onclick="toggleTable('tickets-table')">Mostrar Informações</button>
        </div>
        <div id="tickets-table" class="table-responsive mt-3" style="display: none;">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
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
                            <td>{{ $ticket->title }}</td>
                            <td>{{ $ticket->description }}</td>
                            <td>R$ {{ number_format($ticket->price, 2, ',', '.') }}</td>
                            <td>{{ $ticket->quantity }}</td>
                            <td>{{ $ticket->sold_count }}</td>
                            <td>R$ {{ number_format($ticket->revenue, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Botões de Exportação -->
    <div class="mt-5 text-center">
        <a href="/events/{{ $event->id }}/export/csv" class="btn btn-success mr-2">Exportar para CSV</a>
        <a href="/events/{{ $event->id }}/export/xlsx" class="btn btn-primary">Exportar para Excel</a>
    </div>
</div>

<!-- Script para alternar tabelas -->
<script>
    function toggleTable(tableId) {
        const table = document.getElementById(tableId);
        if (table.style.display === 'none' || table.style.display === '') {
            table.style.display = 'block';
        } else {
            table.style.display = 'none';
        }
    }
</script>

@endsection
