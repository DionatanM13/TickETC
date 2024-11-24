<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - TickEtc</title>

    <!-- Fonte e Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            height: 100vh;
            background-color: #f3f8ff; /* Fallback para navegadores antigos */
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('/img/showcapa.jpg'); /* Caminho correto */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(5px); /* Ajuste o nível de desfoque */
            z-index: -1; /* Envia a camada para trás do conteúdo */
        }


        .register-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-card {
            background-color: #2e073f;
            border: 1px solid #a774e9;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }

        .logo img {
            max-width: 150px;
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        .form-label {
            font-weight: 500;
            color: #f3f8ff;
        }

        .btn-primary {
            background-color: #7e30e1;
            border: none;
        }

        .btn-primary:hover {
            background-color: #49108b;
        }

        .link {
            color: #f3f8ff;
            text-decoration: none;
        }

        .link:hover {
            color: #7e30e1;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <img src="/img/TickEtc.png" alt="TickEtc Logo">
        </div>

        <div class="register-card">
            <h2 class="text-center text-light">Cadastro - TickEtc</h2>

            <!-- Erros de validação -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulário -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" name="name" id="name" class="form-control" required autofocus value="{{ old('name') }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                Eu concordo com os 
                                <a href="{{ route('terms.show') }}" class="link" target="_blank">Termos de Serviço</a> e a 
                                <a href="{{ route('policy.show') }}" class="link" target="_blank">Política de Privacidade</a>.
                            </label>
                        </div>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('login') }}" class="link">Já tem uma conta?</a>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
