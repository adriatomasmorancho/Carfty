@extends('layout.app')

@section('content')
    <div class="menus">
        <form class="menuHome" action="{{ route('ventas.filter') }}" method="get">
            <select name="ordenSelect" onchange="this.form.submit()">
                <option value="" class=" lOrd" <?php if (isset($filtroVentas) && $filtroVentas[1] == '') {
                    echo 'selected';
                } ?>>Ordenación</option>
                <option value="1" class="lOrd" <?php if (isset($filtroVentas) && $filtroVentas[1] == '1') {
                    echo 'selected';
                } ?>>Mas reciente</option>
                <option value="2" class="lOrd" <?php if (isset($filtroVentas) && $filtroVentas[1] == '2') {
                    echo 'selected';
                } ?>>Mas antigua</option>
                <option value="3" class="lOrd" <?php if (isset($filtroVentas) && $filtroVentas[1] == '3') {
                    echo 'selected';
                } ?>>Más barato
                </option>
                <option value="4" class="lOrd" <?php if (isset($filtroVentas) && $filtroVentas[1] == '4') {
                    echo 'selected';
                } ?>>Más caro
                </option>
            </select>
        </form>

        <form class="menuHome" action="{{ route('ventas.estado') }}" method="get">
            <select name="estadoSelect" onchange="this.form.submit()">
                <option value="" class=" lOrd" <?php if (isset($filtroVentas) && $filtroVentas[0] == '') {
                    echo 'selected';
                } ?>>Estado</option>
                <option value="1" class="lOrd" <?php if (isset($filtroVentas) && $filtroVentas[0] == '1') {
                    echo 'selected';
                } ?>>Pendiente</option>
                <option value="2" class="lOrd" <?php if (isset($filtroVentas) && $filtroVentas[0] == '2') {
                    echo 'selected';
                } ?>>Pago aceptado</option>
                <option value="3" class="lOrd" <?php if (isset($filtroVentas) && $filtroVentas[0] == '3') {
                    echo 'selected';
                } ?>>Envio hecho
                </option>
                <option value="4" class="lOrd" <?php if (isset($filtroVentas) && $filtroVentas[0] == '4') {
                    echo 'selected';
                } ?>>Envio recibido
                </option>
                <option value="5" class="lOrd" <?php if (isset($filtroVentas) && $filtroVentas[0] == '5') {
                    echo 'selected';
                } ?>>Pedido Finalizado
                </option>
            </select>
        </form>
        <div class="menuHome">
        <a href="{{ route('ventas.index') }}">
                <div class="btn-filtros">
                    Limpiar filtro
                </div>
            </a>
        </div>
    </div>
    <h2 class="titulo_ventas_compras">Historial de ventas</h2>
    <div class="products__click">
        <div class="productoDisplayer">
            @forelse ($pedidos as $key => $pedido)
                <a href="{{ route('pedido.detalle', ['id' =>  $pedido->pedido_id ]) }}"> 
                    <div class="container-producto">
                        <div class="info-ventas">
                            <div class="info-producto">
                                <p class="elemento_historial">Pedido Nº{{ $pedido->pedido_id }}</p>
                                <p class="elemento_historial">Comprador: {{ $pedido->email }}</p>
                                <p class="elemento_historial">Fecha: {{ date_format(date_create($pedido->fecha), 'd/m/Y'); }}</p>
                                <p class="elemento_historial">Precio Total: {{ $pedido->precio_pedido }}€</p>
                                @if($pedido->status == "Pendiente")
                                <p class="estado">Estado: {{ $pedido->status }}</p>
                                <p class="elemento_historial">Quieres aceptar el pago?</p>
                                <form action="{{ route('pago.index', ['id' => $pedido->pedido_id]) }}" method="get">
                                    <button type="sumbit" class="btn-aceptar">
                                        Aceptar
                                    </button>
                                </form>
                                <form action="{{ route('rechazado.index', ['id' => $pedido->pedido_id]) }}" method="get">
                                    <button type="sumbit" class="btn-rechazar">
                                        Rechazar
                                    </button>
                                </form>
                                @elseif($pedido->status == "Pago aceptado")
                                <p class="estado">Estado: {{ $pedido->status }}</p>
                                <p class="elemento_historial">Pedido enviado?</p>
                                <form action="{{ route('enviado.index', ['id' => $pedido->pedido_id]) }}" method="get">
                                    <button type="sumbit" class="btn-aceptar">
                                        Aceptar
                                    </button>
                                </form>
                                <form action="{{ route('rechazado.index', ['id' => $pedido->pedido_id]) }}" method="get">
                                    <button type="sumbit" class="btn-rechazar">
                                        Rechazar
                                    </button>
                                </form>
                                @elseif($pedido->status == "Envio hecho")
                                <p class="estado">Estado: {{ $pedido->status }}</p>
                                <p class="elemento_historial">Pedido recibido por el cliente?</p>
                                <form action="{{ route('recibido.index', ['id' => $pedido->pedido_id]) }}" method="get">
                                    <button type="sumbit" class="btn-aceptar">
                                        Aceptar
                                    </button>
                                </form>
                                <form action="{{ route('rechazado.index', ['id' => $pedido->pedido_id]) }}" method="get">
                                    <button type="sumbit" class="btn-rechazar">
                                        Rechazar
                                    </button>
                                </form>
                                @elseif($pedido->status == "Envio recibido")
                                <p class="estado">Estado: {{ $pedido->status }}</p>
                                <p class="elemento_historial">Quieres finalizar el pedido?</p>
                                <form action="{{ route('finalizado.index', ['id' => $pedido->pedido_id]) }}" method="get">
                                    <button type="sumbit" class="btn-aceptar">
                                    Finalizar
                                    </button>
                                </form>
                                @elseif($pedido->status == "Pedido cancelado")
                                <p class="estado">Estado: {{ $pedido->status }}</p>
                                @else
                                <p class="estado">Estado: {{ $pedido->status }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="no-hay-productos">
                    <p>No hay pedidos</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="paginacion">
        {{ $pagination }}
    </div>
@endsection