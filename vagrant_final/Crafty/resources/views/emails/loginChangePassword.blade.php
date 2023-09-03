@component('mail::message')
    # E-mail de recuperación de contraseña

    Hola {{ $userEmail->name }} .

    Si desea cambiar su contraseño de acceso a Crafty, haga click al siguiente botón.

    @component('mail::button', ['url' => route('newPassword', $userEmail->id)])
        Restablecer contraseña
    @endcomponent
@endcomponent
