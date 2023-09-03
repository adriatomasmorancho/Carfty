@extends('layout.app')


@section('content')
    <div class="banner" style="background-color: {{ $shop['shop_banner'] }};">
        @if ($shop['shop_image'])
            <img src="{{ asset($shop['shop_image']) }}" />
        @else
            <img src="{{ asset('Imagenes/Iconos/tienda.png') }}" />
        @endif
        <h1>{{ $shop['shop_name'] }}<h1>
    </div>
    <div class="menus-tienda">
        <div class="menuHome barraBusqueda-tienda">
            <form class="formulari-busqueda" action="{{ route('shop.search', ['nombre' => $shop['shop_url']]) }}"
                method="get">
                @csrf
                <input class="search" type="search" placeholder="Buscador..." name="search" value="<?php if (isset($filtroTienda)) {
                    echo $filtroTienda[0];
                } ?>">
                <button class="btn-search" type="submit">Search</button>
            </form>
        </div>

        @if (auth()->check() && auth()->user()->id == $shop['id'])
            <div class="menuHome">
                <a class="margin-btn" href="{{ route('productos.crear') }}">
                    <div class="btn-filtros">
                        Crear
                    </div>
                </a>
            </div>
        @endif
        
        <form class="menuHome" action="{{ route('shop.filter', ['nombre' => $shop['shop_url']]) }}" method="get">
            <select name="ordenSelect" onchange="this.form.submit()">
                <option value="" class=" lOrd" <?php if (isset($filtroTienda) && $filtroTienda[2] == '') {
                    echo 'selected';
                } ?>>Ordenación</option>
                <option value="1" class="lOrd" <?php if (isset($filtroTienda) && $filtroTienda[2] == '1') {
                    echo 'selected';
                } ?>>A-Z</option>
                <option value="2" class="lOrd" <?php if (isset($filtroTienda) && $filtroTienda[2] == '2') {
                    echo 'selected';
                } ?>>Z-A</option>
                <option value="3" class="lOrd" <?php if (isset($filtroTienda) && $filtroTienda[2] == '3') {
                    echo 'selected';
                } ?>>Mas reciente</option>
                <option value="4" class="lOrd" <?php if (isset($filtroTienda) && $filtroTienda[2] == '4') {
                    echo 'selected';
                } ?>>Mas antigua</option>
                <option value="5" class="lOrd" <?php if (isset($filtroTienda) && $filtroTienda[2] == '5') {
                    echo 'selected';
                } ?>>Más barato
                </option>
                <option value="6" class="lOrd" <?php if (isset($filtroTienda) && $filtroTienda[2] == '6') {
                    echo 'selected';
                } ?>>Más caro
                </option>
            </select>

        </form>

        <form class="menuHome" action="{{ route('shop.categoria', ['nombre' => $shop['shop_url']]) }}" method="get">
            <select name="categorySelect" onchange="this.form.submit()">
                <option class="lCat" value="">Categorías</option>
                @foreach ($categorias as $key => $categoria)
                    <option value="{{ $categoria['nombre_categoria'] }}" <?php if (isset($filtroTienda) && $filtroTienda[1] == $categoria['nombre_categoria']) {
                        echo 'selected';
                    } ?> class="lCat">
                        {{ $categoria['nombre_categoria'] }}</option>
                @endforeach
            </select>
        </form>

        @if (auth()->check() && auth()->user()->id == $shop['id'])
            <form class="menuHome" action="{{ route('shop.productos', ['nombre' => $shop['shop_url']]) }}" method="get">
                <select name="tipoSeleccion" onchange="this.form.submit()">
                    <option class="lCat" value="" <?php if (isset($filtroTienda) && $filtroTienda[3] == '') {
                        echo 'selected';
                    } ?>>Todas</option>
                    <option value="1" <?php if (isset($filtroTienda) && $filtroTienda[3] == '1') {
                        echo 'selected';
                    } ?> class="lCat">Productos Habilitados</option>
                    <option value="2" <?php if (isset($filtroTienda) && $filtroTienda[3] == '2') {
                        echo 'selected';
                    } ?> class="lCat">Productos No Habilitados</option>
                    <option value="3" <?php if (isset($filtroTienda) && $filtroTienda[3] == '3') {
                        echo 'selected';
                    } ?> class="lCat">Productos Vendidos</option>
                </select>
            </form>
        @endif

        <div class="menuHome">
            <a href="{{ route('shop.index', ['nombre' => $shop['shop_url']]) }}">
                <div class="btn-filtros">
                    Limpiar filtro
                </div>
            </a>
        </div>
    </div>
    @if (auth()->check() && auth()->user()->id == $shop['id'])
        <div class="products__click">
            <div class="productoDisplayer">
                @forelse ($productos as $key => $producto)
                    <div
                        class="container-producto  @if ($producto['vendido'] == 1) vendido
            @elseif($producto['vendido'] == 0 && $producto['habilitado'] == 0)
                nohabilitado @endif">
                        <div class="img-producto">
                            @if ($imagenes != null)
                                @foreach ($imagenes as $key => $imagen)
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
                            <div class="btn-editar-eliminar">
                                @if ($producto['vendido'] == 0)
                                    <a href="{{ route('productos.editar', ['id' => $producto['producto_id']]) }}">
                                        <img src="{{ asset('Imagenes/Iconos/edit-product.png') }}" alt="account-icon"
                                            id="accountIcon" />
                                    </a>
                                    <a href="{{ route('productos.eliminar', ['id' => $producto['producto_id']]) }}">
                                        <img src="{{ asset('Imagenes/Iconos/delete3.png') }}" alt="account-icon"
                                            id="accountIcon" />
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-hay-productos">
                        <p>No hay productos</p>
                @endforelse
            </div>
        </div>
        <div class="paginacion">
            {{ $pagination }}
        </div>
    @else
        <div class="products__click">
            <div class="productoDisplayer">
                @forelse ($productos as $key => $producto)
                    <a class="route-img" href="{{ route('productos.show', ['id' => $producto['producto_id']]) }}">
                        <div class="container-producto @if ($producto['destacado'] == 1) destacado @endif">
                            <div class="img-producto">
                                @if ($imagenes != null)
                                    @foreach ($imagenes as $key => $imagen)
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
                                    <p class="precio-producto">{{ $producto['precio'] }}</p>
                                </div>
                                <div class="carro carrito-add" id="{{ $producto['producto_id'] }}"></div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="no-hay-productos">
                        <p>No hay productos!!</p>
                @endforelse
            </div>
        </div>

        <div class="paginacion">
            {{ $pagination }}
        </div>
    @endif


@endsection
