@extends('layout.app')


@section('content')
    <div class="menus">

    <form class="menuHome" action="{{ route('productos.filter') }}" method="get">
            <select name="ordenSelect" onchange="this.form.submit()">
                <option value="" class=" lOrd" <?php if (isset($filtro) && $filtro[2] == '') {
                    echo 'selected';
                } ?>>Ordenación</option>
                <option value="1" class="lOrd" <?php if (isset($filtro) && $filtro[2] == '1') {
                    echo 'selected';
                } ?>>A-Z</option>
                <option value="2" class="lOrd" <?php if (isset($filtro) && $filtro[2] == '2') {
                    echo 'selected';
                } ?>>Z-A</option>
                <option value="3" class="lOrd" <?php if (isset($filtro) && $filtro[2] == '3') {
                    echo 'selected';
                } ?>>Mas reciente</option>
                <option value="4" class="lOrd" <?php if (isset($filtro) && $filtro[2] == '4') {
                    echo 'selected';
                } ?>>Mas antigua</option>
                <option value="5" class="lOrd" <?php if (isset($filtro) && $filtro[2] == '5') {
                    echo 'selected';
                } ?>>Más barato
                </option>
                <option value="6" class="lOrd" <?php if (isset($filtro) && $filtro[2] == '6') {
                    echo 'selected';
                } ?>>Más caro
                </option>
            </select>

        </form>

        <form class="menuHome" action="{{ route('productos.categoria') }}" method="get">
            <select name="categorySelect" onchange="this.form.submit()">
                <option class="lCat" value="">Categorías</option>
                @foreach ($categorias as $key => $categoria)
                    <option value="{{ $categoria['nombre_categoria'] }}" <?php if (isset($filtro) && $filtro[1] == $categoria['nombre_categoria']) {
                        echo 'selected';
                    } ?> class="lCat">
                        {{ $categoria['nombre_categoria'] }}</option>
                @endforeach
            </select>
        </form>

        <div class="menuHome">
            <a href="{{ route('home.index', ['id' => 0]) }}">
                <div class="btn-filtros">
                    Limpiar filtro
                </div>
            </a>
        </div>
    </div>
    <div class="products__click">
        <div class="productoDisplayer">
            @forelse ($productos as $key => $producto)
                <a class="route-img" href="{{ route('productos.show', ['id' => $producto['producto_id']]) }}">
                    <div class="container-producto">
                        <div class="img-producto">
                            @if($imagenes !=null)
                                @foreach ($imagenes as $key => $imagen)
                                    @if ($producto['producto_id'] == $imagen['product_id'])
                                        <img src="{{ env('URL_API').$imagen['url'] }}">
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
                            @if (!(auth()->check() && auth()->user()->id == $producto['usuario_id']))
                                <div class="carro carrito-add" id="{{ $producto['producto_id'] }}"></div>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="no-hay-productos">
                    <p>No hay productos</p>
                </div>
            @endforelse


        </div>
    </div>

    <div class="paginacion">
        {{ $pagination }}
    </div>
@endsection
