<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TickEtc</title>

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
            background-image: url('/img/events/_showcapa.jpg'); /* Caminho correto */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(5px); /* Ajuste o nível de desfoque */
            z-index: -1; /* Envia a camada para trás do conteúdo */
        }

        .login-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
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

        .remember-me {
            display: flex;
            align-items: center;
            color: #f3f8ff;
        }

        .remember-me input {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo img-fluid rounded-circle">
            <img src="/img/TickEtc.png" alt="TickEtc Logo">
        </div>

        <div class="login-card">
            <h2 class="text-center text-light">Login - TickEtc</h2>

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
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required autofocus value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="mb-3 remember-me">
                    <input type="checkbox" id="remember_me" name="remember">
                    <label for="remember_me">Lembrar de mim</label>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="link">Esqueceu sua senha?</a>
                    @endif

                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
