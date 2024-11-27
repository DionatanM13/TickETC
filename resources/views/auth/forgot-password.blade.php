<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci a Senha - TickEtc</title>

    <!-- Fonte e Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            height: 100vh;
            background-color: #f3f8ff;
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
            background-image: url('/img/events/_showcapa.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(5px);
            z-index: -1;
        }

        .reset-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .reset-card {
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

        .text-description {
            color: #f3f8ff;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            text-align: center;
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
    <div class="reset-container">
        <div class="logo">
            <img src="/img/TickEtc.png" alt="TickEtc Logo">
        </div>

        <div class="reset-card">
            <h2 class="text-center text-light">Recuperar Senha</h2>

            <!-- Mensagem de descrição -->
            <p class="text-description">
                Esqueceu sua senha? Sem problemas! Informe seu email e enviaremos um link para redefinição de senha.
            </p>

            <!-- Mensagem de sucesso -->
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

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
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required autofocus value="{{ old('email') }}">
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('login') }}" class="link">Voltar ao Login</a>
                    <button type="submit" class="btn btn-primary">Enviar Link</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
