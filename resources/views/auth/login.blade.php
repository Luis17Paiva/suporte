<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/Auth/login.css') }}" />
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form method="POST" action="{{ route('login') }}" class="sign-in-form">
                    @csrf
                    @if (session('error'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ session('error') }}</strong>
                        </span>
                    @endif

                    <h2 class="title">Login</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input id="email1" type="text" placeholder="Email"
                            class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input id="password1" type="password" placeholder="Senha"
                            class="form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="current-password">
                        @error('password1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Me lembre') }}
                        </label>
                    </div>
                    <input type="submit" value="Login" class="btn solid" />
                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Esqueceu a senha?') }}
                        </a>
                    @endif
                </form>
                <form method="POST" action="{{ route('register') }}" class="sign-up-form">
                    @csrf
                    @if (session('error'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ session('error') }}</strong>
                        </span>
                    @endif
                    <h2 class="title">Registrar-se</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input id="name" type="text" placeholder="Nome"
                            class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" required autocomplete="name" autofocus>
                    </div>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input id="E-mail" type="email" placeholder="Email"
                            class="form-control @error('email') is-invalid @enderror" name="E-mail"
                            value="{{ old('email') }}" required autocomplete="email">

                    </div>
                    @error('E-mail')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" placeholder="Senha"
                            class="form-control @error('password') is-invalid @enderror" name="Senha" required
                            autocomplete="new-password">
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input id="password-confirm" type="password" placeholder="Confirme sua senha"
                            class="form-control" name="Senha_confirmation" required autocomplete="new-password">
                    </div>
                    @error('Senha')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <button type="submit" class="btn" value="Sign up">
                        {{ __('Register') }}
                    </button>
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Novo aqui?</h3>
                    <p> Suporte LS Technologies</p>
                    <button class="btn transparent" id="sign-up-btn">
                        Registrar-se
                    </button>
                </div>
               
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>Já tem cadastro?</h3>
                    <p> Suporte LS Technologies</p>
                    <button class="btn transparent" id="sign-in-btn">
                        Login
                    </button>
                </div>
                
            </div>
        </div>
    </div>

    <script src="{{ asset('js/Auth/login.js') }}"></script>
</body>

</html>
