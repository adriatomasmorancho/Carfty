<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href={{ asset('css/style.css') }}>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <title>Login</title>
</head>

<body>

    <section id="card">
        <div class="card__container">
            <div class="card__title">
                <span class="material-symbols-outlined">
                    lock
                </span>
                <h3 class="card__title__h3"> Restablecer contraseña </h3>
            </div>
            <div class="card__content">
                <div class="card__info">
                    <p>¿Necesita su contraseña? Introduzca su dirección de correo electrónico en el siguiente
                        formulario. <br><br>

                        Le enviaremos un correo electrónico de cambio de contraseña a su dirección de correo electrónico
                        si
                        su dirección consta en nuestro sistema. <br><br>

                        Siga las instrucciones del correo electrónico para restablecer su contraseña. <br><br>

                        Si no recibe ningún correo electrónico, compruebe el buzón de correo no deseado. <br><br></p>
                </div>
                <form action="{{ route('login.validateEmail') }}" method="post">
                    @csrf
                    <div class="card__email">
                        <div class="card__emailContainer">
                            <h2 class="card__emailContainer__h2">Correo electrónico</h2>
                            <input type="text" class="card__emailContainer__input" name="emailPass">
                            @if (session()->has('messageEmailVerify'))
                                <div class="messageEmailVerify">

                                    {{ session()->get('messageEmailVerify') }}

                                </div>
                            @elseif(session()->has('messageEmailError'))
                                <div class="messageEmailError">

                                    {{ session()->get('messageEmailError') }}

                                </div>
                            @endif
                            <button type="submit" class="card__emailContainer__button">
                                Cambiar Contraseña
                            </button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>


</html>
