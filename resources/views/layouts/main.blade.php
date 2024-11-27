<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <link rel="shortcut icon" type="imagex/png" href="/img/icon.ico">
    

    <!-- Fonte -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="/css/styles.css">
    <script src="/js/scripts.js"></script>
</head>
<body style="padding: 0; height: 100%; display: flex; flex-direction: column;">

<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- Logo e Nome -->
            <a href="/" class="navbar-brand">
                <img src="/img/TickEtc.png" alt="TICKETC" width="40">
                <h5>TICKETC</h5>
            </a>

            <!-- Botão de Toggle para dispositivos pequenos -->
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Itens do menu (responsivo) -->
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav ms-auto"> <!-- ms-auto para alinhar à direita -->
                    <li class="nav-item">
                        <a href="/events/create" class="nav-link">CRIAR EVENTO</a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a href="/dashboard" class="nav-link">MEUS EVENTOS</a>
                    </li>
                    <li class="nav-item">
                        <form action="/logout" method="POST" class="d-inline">
                            @csrf
                            <a href="/logout" class="nav-link" 
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                SAIR
                            </a>
                        </form>
                    </li>
                    @endauth

                    @guest
                    <li class="nav-item">
                        <a href="/login" class="nav-link">FAZER LOGIN</a>
                    </li>
                    <li class="nav-item">
                        <a href="/register" class="nav-link">CADASTRE-SE</a>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>

<main style="flex: 1">
    <div class="container-fluid">
        <div class="row">
            @if (session('msg-bom'))
                <p class="msg-bom" style="background-color: #c1eecb;
    color: #155724;
    border: 1px solid #64ba78;
    width: 100%;
    margin-bottom: 0px;
    text-align: center;
    padding: 10px;">{{ session('msg-bom') }}</p>
            @elseif (session('msg-ruim'))
                <p class="msg-ruim" style=".msg-ruim {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    width: 100%;
    margin-bottom: 0px;
    text-align: center;
    padding: 10px;
}">{{ session('msg-ruim') }}</p>    
            @endif
            @yield('content')
        </div>
    </div>
</main>

<footer class="bg-dark text-light py-4" style="position: relative; margin-top: 15px;">
    <div class="container">
        <div class="row text-center text-md-start">
            <!-- Seção Sobre -->
            <div class="col-md-4 mb-3">
                <h5 class="fw-bold">Sobre Nós</h5>
                <p>
                    O TICKETC é sua plataforma confiável para adquirir ingressos para eventos de todas as categorias. 
                    Fácil, rápido e seguro.
                </p>
            </div>

            <!-- Seção Links Rápidos -->
            <div class="col-md-4 mb-3">
                <h5 class="fw-bold">Links Rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="/login" class="text-light text-decoration-none">Entrar</a></li>
                    <li><a href="/register" class="text-light text-decoration-none">Cadastrar</a></li>
                    <li><a href="/events/create" class="text-light text-decoration-none">Criar Evento</a></li>
                </ul>
            </div>

            <!-- Seção Redes Sociais -->
            <div class="col-md-4 mb-3 text-md-end">
                <h5 class="fw-bold">Tecnologias Utilizadas</h5>
                <div class="d-flex justify-content-center justify-content-md-end gap-3">
                    <a href="https://laravel.com" class="text-light" target="_blank" aria-label="Facebook">
                        <ion-icon name="logo-laravel" class="bi bi-instagram fs-4"></ion-icon>
                    </a>
                    <a href="https://developer.mozilla.org/en-US/docs/Glossary/HTML5" class="text-light" target="_blank" aria-label="Twitter">
                        <ion-icon name="logo-html5" class="bi bi-instagram fs-4"></ion-icon>
                    </a>
                    <a href="https://developer.mozilla.org/pt-BR/docs/Web/CSS" class="text-light" target="_blank" aria-label="Instagram">
                        <ion-icon name="logo-css3" class="bi bi-instagram fs-4"></ion-icon>
                    </a>
                    <a href="https://linkedin.com" class="text-light" target="_blank" aria-label="LinkedIn">
                        <ion-icon name="logo-github" class="bi bi-linkedin fs-4"></ion-icon>
                    </a>
                    <a href="https://linkedin.com" class="text-light" target="_blank" aria-label="LinkedIn">
                        <ion-icon name="logo-javascript" class="bi bi-linkedin fs-4"></ion-icon>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="mb-0">TICKETC &copy; 2024 - Todos os direitos reservados.</p>
        </div>
    </div>
</footer>



<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
<!-- CSS -->
<link rel="stylesheet" href="/css/styles.css">
</html>
