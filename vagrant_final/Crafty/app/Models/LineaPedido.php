<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LineaPedido extends Model
{
    use HasFactory;

    public static function mostrarLineaPedidos($id)
    {
        $query = DB::table('linea_pedidos')
        ->join("productos", "productos.id", "=", "linea_pedidos.producto_id")
        ->where('linea_pedidos.pedido_id', '=', $id)
        ->get();

        return $query;

    }
}
