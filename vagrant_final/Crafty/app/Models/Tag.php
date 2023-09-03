<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    public static function mostrarTags()
    {
        return  Tag::all();
    }

    public static function mostrarTagsSeleccionados($tags_seleccionados)
    {
        $query = DB::table('tags');

        foreach ($tags_seleccionados as $tag) {
            $query->orWhere('nombre_tag', '=', $tag);
        }
    
        $results = $query->get();
    
        return $results;
    }

    public static function mostrarTagsProducto($id){

        $query = Tag::join("tags_productos", "tags_productos.tag_id", "=", "tags.id")
        ->where('tags_productos.producto_id', "=", $id)
        ->get();
           
        return  $query;
        }

}
