<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href={{ asset('css/style.css') }}>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
    <title>Login</title>
</head>

<body>
    <div class="container__login">
        <div>
            <div>
                @if (session()->has('messageRegister'))
                    <div id="messageRegister">

                        {{ session()->get('messageRegister') }}
                        <button class="messaRegister__buttonCancel">X</button>
                    </div>
                @endif
            </div>
        </div>


        <section id="login">
            <div class="login__container">
                <form class="login__formLogin" id="login-form">
                    @csrf
                    <div class="login_e">
                        <div class="login__logo">
                            <h3>Bienvenido a</h3>
                            <a href="{{ route('landing.index') }}">
                                <img class="login__logo__image"
                                    src="{{ asset('Imagenes/imagenesCategoriaProductos/Logo/logo_CrafTy.png') }}"
                                    alt="logo" />
                            </a>
                        </div>
                        <div class="login__subtitulo">
                            <h4>Para clientes que aprecian lo echo a mano</h4>
                        </div>
                    </div>

                    <div class="error"></div>

                    <div class="login__inputs_elements login_e">
                        <div class="login__element">
                            <h3 class="login__element__h3">
                                Correo</h3>
                            <input class="login__element__input" placeholder="Correo" name="email"
                                type="text" value="{{old('email')}}"></input>
                                <div class="error-email"></div>
                        </div>
                        <div class="login__element">
                            <h3 class="login__element__h3">Contraseña</h3>
                            <input class="login__element__input" placeholder="Contraseña" name="password"
                                type="password" value="{{old('email')}}"></input>
                                <div class="error-password"></div>
                        </div>
                        <div class="login__element olvidar">
                            <a class="login__element__modal" href="{{route('login.rememberPassword')}}">¿Has olvidado la contraseña?</a>
                        </div>
                    </div>
                    <div class="login__button login_e">
                        <div class="login__aLogin">
                            <a href="{{ route('register.index') }}">Crea una cuenta</a>
                        </div>
                        <button class="login__element__button">Login</button>
                    </div>


                </form>

            </div>

        </section>

    </div>

    <script src={{ asset('js/login.js') }}></script>
</body>


</html>
