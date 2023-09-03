@extends('layout.app')

@section('content')
    <div class="landing-banner">
        <div class="banner-container">
            <a href="{{ route('landing.index') }}">
                <img class="banner__logo" src="{{ asset('Imagenes/imagenesCategoriaProductos/Logo/logo_crafty_blanco.png') }}"
                    alt="logo" />
            </a>
            <div class="banner__texto">
                <h4><strong>¡Bienvenidos a CrafTy!</strong>
                    Aquí encontrarás lo mejor en productos echos a mano por artesanos talentosos.</h4>
                <p> Navega por nuestra amplia selección y descubre piezas únicas y especiales que no encontrarás en ningún
                    otro lugar. ¡Compra ahora y apoya a la comunidad de artesanos locales!</p>
            </div>
        </div>
    </div>
    <div class="landing">
        <div class="landing-container">
            @foreach ($tags as $key => $tag)
                <a class="landing-tag" href="{{ route('home.index', ['id' => $tag->id]) }}">
                    <div>{{ $tag->nombre_tag }}</div>
                </a>

                <div class="productoDisplayerLanding landing-products">
                    @foreach ($productos[$tag->nombre_tag] as $key => $producto)
                        <a class="route-img" href="{{ route('productos.show', ['id' => $producto['producto_id']]) }}">
                            <div class="container-producto">
                                <div class="img-producto">
                                    @if ($imagenes != null)
                                        @foreach ($imagenes[$tag->nombre_tag] as $key => $imagen)
                                            @if ($producto['producto_id'] == $imagen['product_id'])
                                                <img src="{{ env('URL_API') . $imagen['url'] }}">
                                            @endif
                                        @endforeach
                                    @else
                                        <img src="">
                                    @endif
                                </div>
                                <div class="info">
                                    <div class="info-producto">
                                        <p class="nombre-producto">{{ $producto['nombre_producto'] }}</p>
                                        <p class="precio-producto">{{ $producto['precio'] }}€</p>
                                    </div>
                                    {{-- <div class="carro carrito-add" id="{{ $producto['producto_id'] }}"></div> --}}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endforeach

            <a class="landing-tag" href="{{ route('home.index', ['id' => 0]) }}">
                MOSTRAR TODOS LOS PRODUCTOS
                <a>

        </div>
    </div>
@endsection
