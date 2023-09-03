@extends('layout.app')

@section('content')
    <form action="{{ route('generatePDF') }}" method="POST">
        @csrf

        <h2 class="titulo_pedido_precio separacion_titulo">Detalle pedido</h2>
        <div class="table-container">


            <table>

                <thead>
                    <tr>
                        <th>Tienda</th>
                        <th>Correo del vendedor</th>
                        <th>Correo del comprador</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Fecha Pedido</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($linea_pedidos as $key => $pedido)
                        <tr>
                            <td>
                                <input type="hidden" name="shop_name[]" value="{{ $pedido_vendedor->shop_name }}" />
                                {{ $pedido_vendedor->shop_name }}
                            </td>
                            <td>
                                <input type="hidden" name="email_vendedor[]" value="{{ $pedido_vendedor->email }}" />
                                {{ $pedido_vendedor->email }}
                            </td>
                            <td>
                                <input type="hidden" name="email_comprador[]" value="{{ $pedido_comprador->email }}" />
                                {{ $pedido_comprador->email }}
                            </td>
                            <td>
                                <input type="hidden" name="nombre_producto[]" value="{{ $pedido->nombre_producto }}" />
                                {{ $pedido->nombre_producto }}
                            </td>
                            <td>
                                <input type="hidden" name="precio[]" value="{{ $pedido->precio }}" />
                                {{ $pedido->precio }} €
                            </td>
                            <td>
                                <input type="hidden" name="fecha[]" value="{{ $pedido_comprador->fecha }}" />
                                {{ date_format(date_create($pedido_comprador->fecha), 'd/m/Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
        <h2 class="titulo_pedido_precio"> Precio Total Pedido: {{ $pedido_comprador->precio_pedido }} €</h2>
        <input type="hidden" name="precio_total" value="{{ $pedido_comprador->precio_pedido }}" />
        {{-- {{ $pedido_comprador->precio_pedido }} € --}}
        <div class="center-btn">
            <button class="login__element__button" type="submit">Descargar PDF</button>
        </div>
    </form>
@endsection
