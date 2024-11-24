@extends('layouts.main')

@section('title', 'Criar Evento')

@section('content')

<div id="event-create-container" class="col-md-8 offset-md-2 mt-5">
    <h1 class="text-center">Crie seu Evento</h1>
    <form action="/events" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Imagem de capa -->
        <div class="form-group mb-3">
            <label for="image">Imagem de Capa:</label>
            <input type="file" name="image" id="image" class="form-control-file">
        </div>

        <!-- Nome do evento -->
        <div class="form-group mb-3">
            <label for="title">Nome do Evento:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Nome do evento" required>
        </div>

        <!-- Data e Hora -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="date">Data do Evento:</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="col-md-6">
                <label for="time">Hora do Evento:</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>
        </div>

        <!-- Evento com mais de um dia -->
        <div class="form-group mb-3">
            <label for="days">O evento dura mais de um dia?</label>
            <select name="days" id="days" class="form-control">
                <option value="0">Não</option>
                <option value="1">Sim</option>
            </select>
        </div>

        <!-- Data final -->
        <div class="form-group mb-3" id="finalDate" style="display: none;">
            <label for="finalDate">Data Final do Evento:</label>
            <input type="date" class="form-control" id="finalDate" name="finalDate">
        </div>

        <!-- Cidade e Local -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="cep">CEP:</label>
                <input type="text" class="form-control" id="cep" name="cep" placeholder="Digite o CEP">
            </div>
            <div class="col-md-4">
                <label for="city">Cidade:</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Cidade" >
            </div>
            <div class="col-md-4">
                <label for="local">Local do Evento:</label>
                <input type="text" class="form-control" id="local" name="local" placeholder="Local do evento">
            </div>
        </div>

        <!-- Tamanho do Evento -->
        <div class="form-group mb-3">
            <label for="size">Tamanho do Evento:</label>
            <select name="size" id="size" class="form-control">
                <option value="pequeno">Pequeno: até 200 Participantes</option>
                <option value="medio" selected>Médio: até 600 Participantes</option>
                <option value="grande">Grande: +1000 Participantes</option>
            </select>
        </div>

        <!-- Evento Privado -->
        <div class="form-group mb-3">
            <label for="private">Evento Privado:</label>
            <select name="private" id="private" class="form-control">
                <option value="0">Não</option>
                <option value="1">Sim</option>
            </select>
        </div>

        <!-- Domínio do Email -->
        <div class="form-group mb-3" id="campoDominio" style="display: none;">
            <label for="dominio">Email Permitido:</label>
            <div class="input-group">
                <span class="input-group-text" style="height:32px;">@</span>
                <input type="text" class="form-control" id="dominio" name="dominio" placeholder="dominio.com">
            </div>
        </div>

        <!-- Descrição -->
        <div class="form-group mb-3">
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Descrição do evento"></textarea>
        </div>

        <!-- Categorias -->
        <div class="form-group mb-3">
            <label for="categories">Categorias:</label>
            <div class="row">
                @foreach (['Show', 'Educativo', 'Esportivo', 'Feira', 'Palestra', 'Reunião'] as $category)
                <div class="col-md-4">
                    <input type="checkbox" name="categories[]" value="{{ $category }}"> {{ $category }}
                </div>
                @endforeach
            </div>
        </div>

        <!-- Botão -->
        <div class="d-flex justify-content-center">
            <input type="submit" class="btn btn-success btn-lg" value="Criar Evento">
        </div>
    </form>
</div>

<script src="/js/scripts.js"></script>
<script>
    document.getElementById('cep').addEventListener('blur', async function () {
        const cep = this.value.replace(/\D/g, ''); // Remove caracteres não numéricos
        if (cep.length === 8) { // Verifica se o CEP tem 8 dígitos
            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                if (!response.ok) throw new Error('Erro ao buscar CEP');

                const data = await response.json();

                if (data.erro) {
                    alert('CEP não encontrado.');
                } else {
                    // Preenche os campos com os dados retornados pela API
                    document.getElementById('city').value = data.localidade;
                    document.getElementById('local').value = data.logradouro || data.bairro || 'N/A';
                }
            } catch (error) {
                alert('Erro ao buscar o CEP. Tente novamente.');
                console.error(error);
            }
        } else {
            alert('Por favor, insira um CEP válido.');
        }
    });
</script>

@endsection
