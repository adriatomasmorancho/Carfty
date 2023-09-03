@component('mail::message')
    # E-mail de bienvenida


    Hola {{ $registerUser->name }} bienvenido a Crafty


    Lo primero que debes hacer es confirmar tu correo electrónico haciendo clic en el siguiente enlace

    @component('mail::button', ['url' => route('verify_email', ['verification_code' => $registerUser->verification_code] )])
        Clic para confirmar tu email
    @endcomponent

@endcomponent

