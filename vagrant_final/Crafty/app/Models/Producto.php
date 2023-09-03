<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use SebastianBergmann\Type\VoidType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_producto',
        'descripcion',
        'precio',
        'producto_image',
        'habilitado',
        'vendido',
    ];


    public static function mostrarProductos($id)
    {
        $query = Producto::select('productos.*', 'productos.id as producto_id', 'shop.usuario_id')
            ->distinct('productos.id')
            ->join("tags_productos", "tags_productos.producto_id", "=", "productos.id")
            ->join("tags", "tags.id", "=", "tags_productos.tag_id")
            ->join("shop", "shop.producto_id", "=", "productos.id")
            ->where('productos.habilitado', "=", "1")
            ->where('productos.vendido', "=", "0");

        if (!empty($id)) {
            $query->where('tags.id', '=',  $id);
        }

        $query->orderByDesc('productos.updated_at');

        // $urlApi = env('URL_API', 'http://127.0.0.1%27/');
        // $response = Http::get($urlApi . '/obtenerImagePrincipal');

        // $imagenes = $response->json();

        // //dd($imagenes);

        // foreach ($query->get() as $producto) {
        //     $producto_id = $producto->id;

        //     foreach ($imagenes as $key => $imagen) {
        //         if (in_array($producto_id, $imagen)) {

        //             $producto->producto_image = $imagenes[$key]['url'];
        //             $producto->save();
        //         }
        //     }
        // }

        return $query;
    }

    public static function mostrarProductosTienda($id)
    {
        $query = Producto::join("shop", "shop.producto_id", "=", "productos.id")
            ->join("users", "users.id", "=", "shop.usuario_id")
            ->where('users.id', '=',  $id);

        if(!(auth()->check() && auth()->user()->id == $id)){
            $query->where('productos.habilitado', "=", "1")
            ->where('productos.vendido', "=", "0")
            ->orderByDesc('productos.destacado');
        }
        
        $query->orderByDesc('productos.updated_at');


        return $query;
    }

    public static function mostrarProductosLanding($tag)
    {
        return Producto::join("tags_productos", "tags_productos.producto_id", "=", "productos.id")
            ->join("tags", "tags.id", "=", "tags_productos.tag_id")
            ->where('tags.nombre_tag', 'LIKE', '%' . $tag . '%')
            ->where('productos.habilitado', "=", "1")
            ->where('productos.vendido', "=", "0")
            ->inRandomOrder()
            ->limit(5)
            ->get();
    }

    /* puedes filtar por un buscador , por categorias o por ordenación*/

    public static function mostrarProductosFiltrados($busqueda, $categoria, $ordenacion, $id, $tipo)
    {
        try {

            $query = Producto::join("categorias_productos", "categorias_productos.producto_id", "=", "productos.id")
                ->join("categorias", "categorias.id", "=", "categorias_productos.categoria_id")
                ->join("shop", "shop.producto_id", "=", "productos.id");


            if($id){
                $query->where('shop.usuario_id', "=", $id);


                if(auth()->check() && auth()->user()->id == $id) {


                    if ($tipo == 1) {
                        $query->where('productos.habilitado', "=", "1")
                        ->where('productos.vendido', "=", "0");
                    }


                    if ($tipo == 2) {
                        $query->where('productos.habilitado', "=", "0");
                    }


                    if ($tipo == 3) {
                        $query->where('productos.vendido', "=", "1");
                    }


                }else{
                    $query->where('productos.habilitado', "=", "1")
                    ->where('productos.vendido', "=", "0");  
                }
            }else{
                $query->where('productos.habilitado', "=", "1")
                ->where('productos.vendido', "=", "0");  
            }

            if (!empty($busqueda)) {
                $query->where('productos.nombre_producto', 'LIKE', '%' . $busqueda . '%');
                Log::channel('desarrollo')->info('enttrs');
            }

            if (!empty($categoria)) {
                $query->where("categorias.nombre_categoria", "=", $categoria);
            }

            if (!empty($ordenacion)) {
                switch ($ordenacion) {
                    case 1:
                        $query->orderBy('productos.nombre_producto');
                        break;
                    case 2:
                        $query->orderByDesc('productos.nombre_producto');
                        break;
                    case 3:;
                        $query->orderByDesc('productos.updated_at');
                        break;
                    case 4:
                        $query->orderBy('productos.updated_at');
                        break;
                    case 5:
                        $query->orderBy('productos.precio');
                        break;
                    case 6:
                        $query->orderByDesc('productos.precio');
                        break;
                }
            }
            $query->orderByDesc('productos.updated_at');

            return $query;

        } catch (Exception $e) {
            Log::channel('desarrollo')->info('Exception error' . $e->getMessage());
        }
    }
}
