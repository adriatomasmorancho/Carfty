<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    public static function mostrarCategorias(){
        return  Categoria::all();;
    }

    public static function mostrarCategoriaProducto($id){

    $query = Categoria::join("categorias_productos", "categorias_productos.categoria_id", "=", "categorias.id")
    ->where('categorias_productos.producto_id', "=", $id)
    ->first();
       
    return  $query;
    }
}
