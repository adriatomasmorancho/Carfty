@extends('layout.app')

@section('content')
    <div id="chat_element_div">
        <div class="container-chat" id="chat-list">
            <h2 class="color-chat">Chats</h2>
            @forelse ($chats as $chat)
                @if ($chat->usuario_id != auth()->user()->id)
                    <a href="{{ route('mostrar_chat.index', ['id' => $chat->chat_id]) }}">
                    <div class="chat_element_div_hilo{{ in_array($chat->chat_id, $arrayChatsMarcados) ? ' marcado' : '' }}">
                        {{ $chat->name }} - {{ $chat->fecha }}
                    </div>
                    </a>
                @endif
            @empty
                <div class="no-hilos">No hay ningun chat activo</div>
            @endforelse
        </div>
        @if ($id_chat != 0)
            <div class="container-chat" id="message-box">
            <a href="{{ route('pedido.detalle', ['id' =>   $id_chat]) }}"> 
                <h2 class="titulo_chat_actual">Chat Actual</h2>
            </a>
                <div id="chat-messages" class="scroll-chat">
                    @if (!empty($mensajes))
                        @foreach ($mensajes as $mensaje)
                            @if (auth()->user()->id == $mensaje->usuario_id)
                                <div class="mesaje_auth">
                                    <p class="color_mesaje_auth">
                                        {{ $mensaje->mensaje }}
                                    </p>
                                    <p>
                                        {{ date_format(date_create($mensaje->fecha), 'd/m/Y H:i') }}
                                    </p>
                                </div>
                            @elseif($mensaje->usuario_id == 0)
                                @if ($mensaje->mensajes_automaticos_usuario_id == auth()->user()->id)
                                    <div class="mesaje_auto">
                                        {{ $mensaje->mensaje }}
                                        {{ date_format(date_create($mensaje->fecha), 'd/m/Y H:i') }}
                                    </div>
                                @endif
                            @else
                                <div class="mesaje_noauth">
                                    <p class="color_mesaje_noauth">
                                        {{ $mensaje->mensaje }}
                                    </p>
                                    <p>
                                        {{ date_format(date_create($mensaje->fecha), 'd/m/Y H:i') }}
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
                <form class="form-chat" action="{{ route('enviar_mensaje.index') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id_chat }}">
                    <input type="text" id="message-input" name="mensaje" placeholder="Escribe tu mensaje">
                    <button type="submit">Enviar</button>
                    <form>
            </div>
        @else
            <div class="no-chat">Haz clic en cualquier chat</div>
        @endif
    </div>
@endsection
