<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href={{ asset('css/style.css') }}>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
    <title>Register</title>
</head>

<body>
    <section id="register">
        <div class="register__container">
            <form class="register__formRegister" id="register-form">
                @csrf
                <div class="register__inputs">
                    <div class="register_e">
                        <div class="register__logo">
                            <h3>Crea una cuenta en</h3>
                            <a href="{{ route('landing.index') }}">
                                <img class="register__logo__image"
                                    src="{{ asset('Imagenes/imagenesCategoriaProductos/Logo/logo_CrafTy.png') }}"
                                    alt="logo" />
                            </a>
                        </div>
                        <div class="register__subtitulo">
                            <h4>Para clientes que aprecian lo echo a mano</h4>
                        </div>
                    </div>

                    <div class="error"></div>

                    <div class="register_e">
                        <div class="register__element" id="register__username">
                            <h3 class="register__element__h3">
                                Nombre de Usuario</h3>
                            <input class="register__element__input" placeholder="Username" name="name"
                                type="text"></input>
                                <div class="error-user"></div>
                        </div>

                        <div class="register__element ">
                            <h3 class="register__element__h3">
                                Correo electrónico</h3>
                            <input class="register__element__input" placeholder="Correo" name="email"
                                type="text"></input>
                                <div class="error-email"></div>
                        </div>



                        <div class="register__element">
                            <h3 class="register__element__h3">Contraseña</h3>
                            <input class="register__element__input" placeholder="Contraseña" name="password"
                                type="password"></input>
                                <div class="error-password"></div>
                        </div>

                        <div class="register__element">
                            <h3 class="register__element__h3">Repetir Contraseña</h3>
                            <input class="register__element__input" placeholder="Confirmación" name="password_confirmation"
                                type="password"></input>
                        </div>
                    </div>
                    <div class="register_e">
                        <div class="register__button" id="register__button">
                            <div class="register__aLogin">
                                <a href="{{ route('login.index') }}">Prefiero iniciar sesión</a>
                            </div>
                            <button class="register__element__button">Register</button>

                        </div>
                    </div>



                </div>

            </form>

        </div>
    </section>
    <script src={{ asset('js/register.js') }}></script>
</body>

</html>
