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

    <section id="cardPassword">
        <div class="card__ChangePasswordContainer">
            <div class="card__title">
                <span class="material-symbols-outlined">
                    lock
                </span>
                <h3 class="card__title__h3"> Cambiar contraseña</h3>
            </div>
            <div class="card__content">
                <form action="{{ route('updatePasswordValue') }}" method="POST">
                    @csrf
                    <div class="card__Password">
                        <h3 class="card__Password__h3">Contraseña actual</h3>
                        <input class="card__Password__input" type="password" name="currentPassword" id=""
                            value="{{ $userEmail->password }}" readonly>
                    </div>
                    <div class="card__Password">
                        <h3 class="card__Password__h3">Nueva contraseña</h3>
                        <input class="card__Password__input" type="password" name="changePassword" id="">
                    </div>
                    <div class="card__Password">
                        <h3 class="card__Password__h3">Repetir nueva contraseña</h3>
                        <input class="card__Password__input" type="password" name="changePassword2" id="">
                    </div>
                    <button type="submit" class="card__Password__button">
                        Cambiar Contraseña
                    </button>
                </form>
            </div>
    </section>
</body>


</html>