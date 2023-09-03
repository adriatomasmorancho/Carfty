<style>
    .table-container {
        overflow-x: auto;
        justify-content: center;
        align-items: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border: 2px solid #37bc9d;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border: 1px solid #37bc9d;
        text-align: center;
    }

    th {
        background-color: #37bc9d;
        color: white;
    }

    td {
        background-color: white;
        color: black;
    }

    tr:nth-child(odd) {
        background-color: #37bc9d;
        color: white;
    }
</style>




<h2>Detalle pedido</h2>
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

            @foreach ($data['shop_name'] as $key => $value)
                <tr>
                    <td>{{ $value }}</td>
                    <td>{{ $data['email_vendedor'][$key] }}</td>
                    <td>{{ $data['email_comprador'][$key] }}</td>
                    <td>{{ $data['nombre_producto'][$key] }}</td>
                    <td>{{ $data['precio'][$key] }} €</td>
                    <td>{{ date_format(date_create($data['fecha'][$key]), 'd/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h2>Precio Total Pedido: {{ $data['precio_total'] }} €</h2>
</div>

</body>

</html>