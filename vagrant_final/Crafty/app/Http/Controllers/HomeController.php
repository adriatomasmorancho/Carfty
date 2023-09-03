<?php

namespace App\Http\Controllers;


use Exception;
use App\Models\Tag;
use App\Models\User;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Contracts\Session\Session;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public static function peticionUrlImageProduct($productos, $urlPeticion)
    {
        try {
            $data = [];

            foreach ($productos as $producto) {
                $data[] = [
                    'id' => $producto->producto_id
                ];
            }

            $urlApi = env('URL_API', 'http://127.0.0.1/');
            $url = $urlApi . $urlPeticion;

            $response = Http::withoutVerifying()->post($url, [
                'productos' => $data
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                if ($urlPeticion != '/api/eliminarImagesProduct') {

                    $expires = 60 * 2;
                    header('Cache-Control: private');
                    header('Expires:' . gmdate('D,d M Y H:i:s', time() + $expires) . ' GMT');
                    return $responseData['imagenes'];
                }
            } else {
            }
        } catch (Exception $e) {
            Log::error("Error al realizar la petición de URL de imagen del producto: {$e->getMessage()}");
            return redirect()->route('error.generic');
        }
    }


    public function index(string $id)
    {
        Log::channel('desarrollo')->info('Muestra todos los productos de la bbdd');
        session()->forget(['id_busqueda', 'id_categoria', 'id_ordenacion']);
        $productos = Producto::mostrarProductos($id)->paginate(intval(env('PAGINATE_NUM')));
        $imagenes = HomeController::peticionUrlImageProduct($productos, '/api/obtenerImagePrincipal');
        $pagination = $productos->appends(request()->query());
        $categorias = Categoria::mostrarCategorias();
        return view('home.home', compact('productos', 'categorias', 'pagination', 'imagenes'));
    }



    public function search(Request $request)
    {
        try {
            $id = 0;
            $tipo = 0;

            $busqueda = $request->all();
            $id_busqueda = $busqueda['search'] ?? '';
            $id_categoria = session('id_categoria');
            $id_ordenacion = session('id_ordenacion');

            session(["id_busqueda" => $id_busqueda]);

            $session_busqueda = session('id_busqueda');
            Log::channel('desarrollo')->info($session_busqueda . " " . session('id_categoria'));
            $productos = Producto::mostrarProductosFiltrados($session_busqueda, $id_categoria, $id_ordenacion, $id, $tipo)
                ->paginate(intval(env('PAGINATE_NUM')));

            $imagenes = HomeController::peticionUrlImageProduct($productos, '/api/obtenerImagePrincipal');
            $pagination = $productos->appends([
                'search' => $session_busqueda,
                'categorySelect' => $id_categoria,
                'ordenSelect' => $id_ordenacion
            ]);

            $categorias = Categoria::mostrarCategorias();
            $filtro = [$session_busqueda, $id_categoria, $id_ordenacion];

            Log::channel('desarrollo')->info('La funció searcg de HomeController funciona correctament');
            return view('home.home', [
                'productos' => $productos,
                'categorias' => $categorias,
                'filtro' => $filtro,
                'pagination' => $pagination,
                'imagenes' => $imagenes
            ]);
        } catch (Exception $e) {
            Log::error("Error en la función search de HomeController: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }


    public function categoria(Request $request)
    {
        try {
            $id = 0;
            $tipo = 0;

            $busqueda = $request->all();
            $id_busqueda = session('id_busqueda');
            $id_ordenacion = session('id_ordenacion');
            $id_categoria = $busqueda['categorySelect'] ?? '';


            session(["id_categoria" => $id_categoria]);


            $session_categoria = session('id_categoria');

            $productos = Producto::mostrarProductosFiltrados($id_busqueda, $session_categoria, $id_ordenacion, $id, $tipo)
                ->paginate(intval(env('PAGINATE_NUM')));

            Log::channel('desarrollo')->info($productos);

            $imagenes = HomeController::peticionUrlImageProduct($productos, '/api/obtenerImagePrincipal');
            $pagination = $productos->appends([
                'search' => $id_busqueda,
                'categorySelect' => $session_categoria,
                'ordenSelect' => $id_ordenacion
            ]);

            $categorias = Categoria::mostrarCategorias();
            $filtro = [$id_busqueda, $session_categoria, $id_ordenacion];
            Log::channel('desarrollo')->info('La funció categoria de HomeController funciona correctament');
            return view('home.home', [
                'productos' => $productos,
                'categorias' => $categorias,
                'filtro' => $filtro,
                'pagination' => $pagination,
                'imagenes' => $imagenes
            ]);
        } catch (Exception $e) {
            Log::error("Error en la función categoria de HomeController: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }

    public function ordenacio(Request $request)
    {
        try {
            $id = 0;
            $tipo = 0;

            $busqueda = $request->all();
            $id_busqueda = session('id_busqueda');
            $id_categoria = session('id_categoria');
            $id_ordenacion = $busqueda['ordenSelect'] ?? '';


            session(["id_ordenacion" => $id_ordenacion]);

            $session_ordenacion = session('id_ordenacion');

            $productos = Producto::mostrarProductosFiltrados($id_busqueda, $id_categoria, $session_ordenacion, $id, $tipo)
                ->paginate(intval(env('PAGINATE_NUM')));

            $imagenes = HomeController::peticionUrlImageProduct($productos, '/api/obtenerImagePrincipal');
            $pagination = $productos->appends([
                'search' => $id_busqueda,
                'categorySelect' => $id_categoria,
                'ordenSelect' => $session_ordenacion
            ]);


            $categorias = Categoria::mostrarCategorias();
            $filtro = [$id_busqueda, $id_categoria, $session_ordenacion];

            Log::channel('desarrollo')->info('La funció ordenacio de HomeController funciona correctament');
            return view('home.home', [
                'productos' => $productos,
                'categorias' => $categorias,
                'filtro' => $filtro,
                'pagination' => $pagination,
                'imagenes' => $imagenes
            ]);
        } catch (Exception $e) {
            Log::error("Error en la función ordenacion de HomeController: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $categoria = Categoria::mostrarCategoriaProducto($id);
            $tags = Tag::mostrarTagsProducto($id);
            $shop = User::join("shop", "shop.usuario_id", "=", "users.id")
                ->where('shop.producto_id', '=', $id)
                ->first();
            $productos = Producto::join("shop", "shop.producto_id", "=", "productos.id")->where('shop.producto_id', $id)->get();
            $imagenes = HomeController::peticionUrlImageProduct($productos, '/api/obtenerImagesProduct');
            Log::channel('desarrollo')->info('La funció show de HomeController funciona correctament');
            return view('productos.show', ['producto' => Producto::findOrFail($id), 'categoria' => $categoria, 'tags' => $tags, 'shop' => $shop, 'imagenes' => $imagenes]);
        } catch (Exception $e) {
            Log::error("Error en la función show de HomeController: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}