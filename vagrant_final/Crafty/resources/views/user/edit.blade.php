@extends('layout.app')


@section('content')
<div class="login__container">
    <form class="form-create" method="POST" action="{{ route('user.guardar') }}" enctype="multipart/form-data">
        @csrf
        <h2 class="title-h2">Editar perfil</h2>

        @if (session('success'))
        <div class="alert alert-success style-correct">
            {{ session('success') }}
        </div>
        @endif

        <div class="register__email">
            <h3 class="login__element__h3">
                Nombre</h3>
            <input class="login__element__input" name="name" placeholder="Nombre usuario" value="{{ $usuario['name'] }}" type="text"></input>
            @error('name')
            <div class="style-error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <h3 class="login__element__h3">
                Email</h3>
            <input class="login__element__input" value="{{ $usuario['email'] }}" placeholder="Correo electrónico" name="email" type="text"></input>
            @error('email')
            <div class="style-error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <h3 class="login__element__h3">
                Contraseña actual</h3>
            <input class="login__element__input" placeholder="Contraseña" name="password" type="password"></input>
            @error('password')
            <div class="style-error">{{ $message }}</div>
            @enderror

        </div>

        <div>
            <h3 class="login__element__h3">
                Nueva Contraseña</h3>
            <input class="login__element__input" placeholder="Nueva contraseña" name="newPassword" type="password"></input>
            @error('newPassword')
            <div class="style-error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <h3 class="login__element__h3">
                Repetir nueva contraseña</h3>
            <input class="login__element__input" placeholder="Repetir nueva ontraseña" name="repeatNewPassword" type="password"></input>
            @error('repeatNewPassword')
            <div class="style-error">{{ $message }}</div>
            @enderror
        </div>

        <label>Selecciona para obtener una tienda:</label>
        @if ($usuario['shop'] == 0)
        <input type="checkbox" name="shop" id="shop"></input>
        @else
        <input type="checkbox" value="{{ $usuario['shop'] }}" name="shop" id="shop" checked disabled></input>
        <input type="hidden" value="{{ $usuario['shop'] }}" name="shop">
        @endif

        <div id="nombre_tienda" hidden>
            <h3 class="login__element__h3">
                Nombre de la tienda</h3>
            <input class="login__element__input" value="{{ $usuario['shop_name'] }}" placeholder="Nombre tienda" name="shop_name" type="text"></input>
            @error('shop_name')
            <div class="style-error">{{ $message }}</div>
            @enderror
        </div>

        <div id="logo_tienda" hidden>
            <h3 class="login__element__h3">
                Logo de la tienda</h3>
            <input class="login__element__input" type="file" name="shop_image" accept="image/*">
            <img class="image-show" src="{{ asset($usuario['shop_image']) }}" />
        </div>

        <div id="banner_tienda" hidden>
            <h3 class="login__element__h3">
                Color del banner de la tienda</h3>
            <input type="color" value="{{ $usuario['shop_banner'] }}" id="shop_banner" name="shop_banner">
        </div>

        <div>
            <h3 class="login__element__h3">
                Foto de perfil</h3>
            <input class="login__element__input" type="file" name="image" accept="image/*">
            <img class="image-show" src="{{ asset($usuario['image']) }}" />
        </div>
        <div class="create__button">
            <button class="login__element__button__update btn-create" id="actualizar" type="submit">Actualizar Datos</button>
        </div>
    </form>
</div>
@endsection