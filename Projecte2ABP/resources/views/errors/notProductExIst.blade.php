<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href={{ asset('css/style.css') }}>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
    <title>Not Found</title>
</head>

<body>
    <div class="contenedor">
        <h1 class="titulo">¡Ups!</h1>
        <p class="mensaje">Lo siento pero este producto no existe :(</p>
        <a href="{{ route('landing.index') }}">
            <button class="boton">Volver a intentarlo</button>
        </a>
    </div>

</body>

</html>
