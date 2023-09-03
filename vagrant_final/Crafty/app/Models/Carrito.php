<?php

namespace App\Models;

use App\Models\Producto;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrito extends Model
{
    use HasFactory;

    public static function mostrarProductosCarrito($productCarritos)
    {
        //transformarlo en array con split
        //hacer un for llamando a una funcion consulta
        //guardar el producto en un array
        //return array de todos los productos
        Log::channel('desarrollo')->info('arrayProductId ' . 'entra funcion mostrar product carritos');
        $arrayProductos = explode(",", trim($productCarritos));
        $queryProduct = Producto::orderByDesc('updated_at');
        
        foreach ($arrayProductos as $idProducto) {
            Log::channel('desarrollo')->info('arrayProductId ' . $idProducto);
            $queryProduct->orWhere("id", "=", intval($idProducto));
        }


        return $queryProduct->get();
    }
}
