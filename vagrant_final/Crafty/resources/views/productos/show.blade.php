@extends('layout.app')

@section('title', 'Producto')

@section('content')

    <!-- Content page DetalleProductp.php Laravel-->
    <section class="container">
        <div class="ContainerProduct">
            <div class="containerProduct__images">

                @php
                    $imagenPrincipal = array_shift($imagenes);
                @endphp
                <img src="{{ env('URL_API') . $imagenPrincipal['url'] }}" id="imagen-principal">
                <div class="imageProduct">
                    @foreach ($imagenes as $key => $imagen)
                        <img src="{{ env('URL_API') . $imagen['url'] }}" onclick="cambiarMiniatura(this)">
                    @endforeach
                </div>
            </div>

            <div class="containerProduct__info">

                <div class="detailsProducts">
                    <h2 class="titulo_product">{{ $producto['nombre_producto'] }}</h2>
                    <h2>Precio: {{ $producto['precio'] }}€</h2>
                    <h2>Descripción: </h2>
                    <p>
                    {{ $producto['descripcion'] }}
                    </p>
                </div>


                <div class="ContainerCategorias">
                    <h2>Tags</h2>
                    <div class="container-tags">
                        @foreach ($tags as $key => $tag)
                            <div class="tags">
                                <a href="{{ route('home.index', ['id' => $tag['tag_id']]) }}">{{ $tag['nombre_tag'] }}</a>
                            </div>
                        @endforeach
                    </div>

                    <div class="continerProducts_extraInfo">
                        <form action="{{ route('productos.categoria') }}" method="get">
                            <div class="categorias">
                                <input type="hidden" name="categorySelect" value="{{ $categoria['nombre_categoria'] }}" />
                                <h2>Categorias</h2>
                                <button type="submit"> {{ $categoria['nombre_categoria'] }}</button>
                            </div>
                        </form>
                        <div class="shop_image">
                            <h2>Tienda</h2>
                            <a class="logo_tienda" href="{{ route('shop.index', ['nombre' => $shop['shop_url']]) }}">
                                @if ($shop['shop_image'])
                                <img src="{{ asset($shop['shop_image']) }}" />
                                @else
                                <img style="background-color: white" src="{{ asset('Imagenes/Iconos/tienda.png') }}" />
                                @endif
                                <p class="name_shop">{{ $shop['shop_name'] }}</p>
                            </a>
                        </div>
                        @if (!(auth()->check() && auth()->user()->id == $shop['usuario_id']))
                            <div class="products__click">
                                <div class="carro carrito-add" id="{{ $producto['id'] }}"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src={{ asset('js/detalleProducto.js') }}></script>
@endsection
