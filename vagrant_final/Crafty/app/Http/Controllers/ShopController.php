<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Tag;
use App\Models\User;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HomeController;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $nombre)

    {
        try {
            session()->forget(['id_busquedaTienda', 'id_categoriaTienda', 'id_ordenacionTienda', 'id_tipoTienda']);
            $shop = User::where('shop_url', '=', $nombre)
                ->first();
            $productos = Producto::mostrarProductosTienda($shop->id)->paginate(intval(env('PAGINATE_NUM')));
            $categorias = Categoria::mostrarCategorias();
            $imagenes = HomeController::peticionUrlImageProduct($productos, '/api/obtenerImagePrincipal');
            $pagination = $productos->appends(request()->query());

            Log::channel('desarrollo')->info('La funció index de ShopController funciona correctament');

            return view('tienda.tienda', compact('productos', 'pagination', 'shop', 'categorias', 'productos', 'imagenes'));
        } catch (Exception $e) {

            Log::error("Error en la función index: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
            $categorias = Categoria::mostrarCategorias();
            $tags = Tag::mostrarTags();
            Log::channel('desarrollo')->info('La funció create de ShopController funciona correctament');
            return view('productos.create', ['categorias' => $categorias, 'tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $arrayImagen = [];

            $tags = explode(",", $request["seleccionados"]);
            $request->validate(
                [
                    'name' => 'required|string',
                    'description' => 'required|string',
                    'precio' => 'required|integer',
                    'imagePrincipal' => 'required',
                    'image' => 'required',
                    'seleccionados'   => 'required',
                    'categorySelect' => 'required|gt:0',
                ],
                [
                    'categorySelect.gt' => 'Tienes que seleccionar una categoría',
                ]
            );

            $tags = explode(",", $request["seleccionados"]);

            $images = $request->all();


            $arrayImagen[] = [
                'name' => 'imagePrincipal',
                'contents' => $images['imagePrincipal']->get(),
                'filename' => $images['imagePrincipal']->getClientOriginalName()
            ];

            foreach ($images['image'] as $key => $image) {
                $arrayImagen[] = [
                    'name' => 'image[' . $key . ']',
                    'contents' => $image->get(),
                    'filename' => $image->getClientOriginalName()
                ];
            }

            $producto = Producto::create([
                'nombre_producto' => $request['name'],
                'descripcion' => $request['description'],
                'precio' => $request['precio']
            ]);

            $arrayImagen[] = [
                'name' => 'producto_id',
                'contents' => $producto->id
            ];


            if ($image->isValid()) {
                $urlApi = env('URL_API', 'http://127.0.0.1.27/');
                $response = Http::withoutVerifying()->attach($arrayImagen)->post($urlApi . '/api/addImage');
            }

            $categoria_producto = [
                [
                    'categoria_id' => $request['categorySelect'],
                    'producto_id' => $producto->id
                ]
            ];

            $shop = [
                [
                    'usuario_id' => auth()->user()->id,
                    'producto_id' => $producto->id
                ]
            ];

            DB::table('categorias_productos')->insert($categoria_producto);
            DB::table('shop')->insert($shop);

            foreach ($tags as $tag) {
                $tag_producto = [
                    [
                        'tag_id' => $tag,
                        'producto_id' => $producto->id
                    ]
                ];
                DB::table('tags_productos')->insert($tag_producto);
            }

            session()->flash('status', 'Se ha creado el producto correctamente');

            Log::channel('desarrollo')->info('La funció store de ShopController funciona correctament');
            return redirect(route('shop.index', ['nombre' => auth()->user()->shop_url]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $producto = Producto::join("shop", "shop.producto_id", "=", "productos.id")->where('shop.producto_id', '=', $id)->get();
        if ($producto->isNotEmpty()) {
            if ($producto[0]->usuario_id == auth()->user()->id) {
                $imagenes = HomeController::peticionUrlImageProduct($producto, '/api/obtenerImagesProduct');
                //dd($imagenes);
                $categorias = Categoria::mostrarCategorias();
                $tags = Tag::mostrarTags();
                $categoria_producto = DB::table('categorias_productos')->where('producto_id', $id)->first();
                $tags_productos = DB::table('tags_productos')->where('producto_id', $id)->get();
                Log::channel('desarrollo')->info('La funció edit de ShopController funciona correctament');
                return view('productos.edit', ['producto' => Producto::findOrFail($id), 'categorias' => $categorias, 'categoria_producto' => $categoria_producto->categoria_id, 'tags' => $tags, 'tags_productos' => $tags_productos, 'imagenes' => $imagenes]);
            } else {

                return redirect()->route('error.generic');
            }
        } else {

            return redirect()->route('error.product');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $arrayImagen = [];

            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'precio' => 'required|integer',
                'seleccionados'   => 'required',
            ]);

            $producto = Producto::findOrFail($request['id']);

            $name = $request['name'];

            $description = $request['description'];

            $price = $request['precio'];

            $habilitado = $request['habilitado'];

            $tags = explode(",", $request["seleccionados"]);

            $images = $request->all();

            if (isset($images['imagePrincipal'])) {

                $arrayImagen[] = [
                    'name' => 'imagePrincipal',
                    'contents' => $images['imagePrincipal']->get(),
                    'filename' => $images['imagePrincipal']->getClientOriginalName()
                ];
            }

            if (isset($images['image'])) {

                foreach ($images['image'] as $key => $image) {
                    if ($image->isValid()) {
                        $arrayImagen[] = [
                            'name' => 'image[' . $key . ']',
                            'contents' => $image->get(),
                            'filename' => $image->getClientOriginalName()
                        ];
                    }
                }
            }

            if (isset($images['imagePrincipal']) || isset($images['image'])) {
                $arrayImagen[] = [
                    'name' => 'producto_id',
                    'contents' => $producto->id
                ];

                $urlApi = env('URL_API', 'http://127.0.0.1.27/');
                $response = Http::withoutVerifying()->attach($arrayImagen)->post($urlApi . '/api/editImage');
            }


            if (!$habilitado) {
                $producto->habilitado = 0;
            } else {
                $producto->habilitado = $habilitado;
            }

            if ($request['destacado'] != null) {
                $producto->destacado = $request['destacado'];
            }

            $producto->nombre_producto = $name;
            $producto->descripcion = $description;
            $producto->precio = $price;
            $producto->save();

            DB::table('categorias_productos')->where('producto_id', $request['id'])->update(['categoria_id' => $request['categorySelect']]);

            DB::table('tags_productos')
                ->where('producto_id', '=', $request['id'])
                ->delete();

            foreach ($tags as $tag) {
                $tag_producto = [
                    [
                        'tag_id' => $tag,
                        'producto_id' => $producto->id
                    ]
                ];
                DB::table('tags_productos')->insert($tag_producto);
            }

            session()->flash('status', 'Se han guardado los datos correctamente');

            Log::channel('desarrollo')->info('La funció update de ShopController funciona correctament');
            return redirect(route('shop.index', ['nombre' => auth()->user()->shop_url]));
        } catch (Exception $e) {
            Log::error("Error en la función update: {$e->getMessage()}");


            return redirect()->route('error.generic');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::join("shop", "shop.producto_id", "=", "productos.id")->where('shop.producto_id', '=', $id)->get();
        if ($producto->isNotEmpty()) {
            if ($producto[0]->usuario_id == auth()->user()->id) {

                HomeController::peticionUrlImageProduct($producto, '/api/eliminarImagesProduct');

                $producto[0]->delete();

                session()->flash('status', 'El producto se ha eliminado correctamente');

                return redirect(route('shop.index', ['nombre' => auth()->user()->shop_url]));
            } else {
                return redirect()->route('error.generic');
            }
        } else {
            return redirect()->route('error.product');
        }
    }

    public function search(Request $request, string $nombre)
    {
        try {
            $shop = User::where('shop_url', '=', $nombre)
                ->first();

            $id = $shop->id;

            $busqueda = $request->all();
            $id_busqueda = $busqueda['search'] ?? '';
            $id_categoria = session('id_categoriaTienda');
            $id_ordenacion = session('id_ordenacionTienda');
            $id_tipo = session('id_tipoTienda');

            session(["id_busquedaTienda" => $id_busqueda]);

            $session_busqueda = session('id_busquedaTienda');
            $productos = Producto::mostrarProductosFiltrados($session_busqueda, $id_categoria, $id_ordenacion, $id, $id_tipo)
                ->paginate(intval(env('PAGINATE_NUM')));

            $imagenes = HomeController::peticionUrlImageProduct($productos, '/api/obtenerImagePrincipal');
            $pagination =  $productos->appends([
                'search' => $session_busqueda,
                'categorySelect' => $id_categoria,
                'ordenSelect' => $id_ordenacion,
                'tipoSeleccion' => $id_tipo
            ]);

            $categorias = Categoria::mostrarCategorias();
            $filtroTienda = [$session_busqueda, $id_categoria, $id_ordenacion, $id_tipo];
            $shop = User::where('id', '=', $id)
                ->first();
            Log::channel('desarrollo')->info('La funció search de ShopController funciona correctament');

            return view('tienda.tienda', compact('productos', 'pagination', 'shop', 'categorias', 'filtroTienda', 'imagenes'));
        } catch (Exception $e) {
            Log::error("Error en la función search: {$e->getMessage()}");


            return redirect()->route('error.generic');
        }
    }


    public function categoria(Request $request, string $nombre)
    {
        try {
            $shop = User::where('shop_url', '=', $nombre)
                ->first();

            $id = $shop->id;

            $busqueda = $request->all();
            $id_busqueda = session('id_busquedaTienda');
            $id_ordenacion = session('id_ordenacionTienda');
            $id_categoria = $busqueda['categorySelect'] ?? '';
            $id_tipo = session('id_tipoTienda');


            session(["id_categoriaTienda" => $id_categoria]);


            $session_categoria = session('id_categoriaTienda');

            $productos = Producto::mostrarProductosFiltrados($id_busqueda, $session_categoria, $id_ordenacion, $id, $id_tipo)
                ->paginate(intval(env('PAGINATE_NUM')));

            $imagenes = HomeController::peticionUrlImageProduct($productos, '/api/obtenerImagePrincipal');
            $pagination =  $productos->appends([
                'search' => $id_busqueda,
                'categorySelect' => $session_categoria,
                'ordenSelect' => $id_ordenacion,
                'tipoSeleccion' => $id_tipo
            ]);


            $categorias = Categoria::mostrarCategorias();
            $filtroTienda = [$id_busqueda, $session_categoria, $id_ordenacion, $id_tipo];
            $shop = User::where('id', '=', $id)
                ->first();
            Log::channel('desarrollo')->info('La funció categoria de ShopController funciona correctament');
            return view('tienda.tienda', [
                'productos' =>  $productos,
                'categorias' => $categorias,
                'filtroTienda' => $filtroTienda,
                'pagination' => $pagination,
                'shop' => $shop,
                'imagenes' => $imagenes,
            ]);
        } catch (Exception $e) {
            Log::error("Error en la función categoria: {$e->getMessage()}");


            return redirect()->route('error.generic');
        }
    }

    public function ordenacio(Request $request, string $nombre)
    {
        try {
            $shop = User::where('shop_url', '=', $nombre)
                ->first();

            $id = $shop->id;

            $busqueda = $request->all();
            $id_busqueda = session('id_busquedaTienda');
            $id_categoria = session('id_categoriaTienda');
            $id_ordenacion = $busqueda['ordenSelect'] ?? '';
            $id_tipo = session('id_tipoTienda');


            session(["id_ordenacionTienda" => $id_ordenacion]);

            $session_ordenacion = session('id_ordenacionTienda');

            $productos = Producto::mostrarProductosFiltrados($id_busqueda, $id_categoria, $session_ordenacion, $id, $id_tipo)
                ->paginate(intval(env('PAGINATE_NUM')));

            $imagenes = HomeController::peticionUrlImageProduct($productos, '/api/obtenerImagePrincipal');

            $pagination = $productos->appends([
                'search' => $id_busqueda,
                'categorySelect' => $id_categoria,
                'ordenSelect' => $session_ordenacion,
                'tipoSeleccion' => $id_tipo
            ]);


            $categorias = Categoria::mostrarCategorias();
            $filtroTienda = [$id_busqueda, $id_categoria, $session_ordenacion, $id_tipo];
            $shop = User::where('id', '=', $id)
                ->first();

            Log::channel('desarrollo')->info('La funció ordenacio de ShopController funciona correctament');
            return view('tienda.tienda', [
                'productos' => $productos,
                'categorias' => $categorias,
                'filtroTienda' => $filtroTienda,
                'pagination' => $pagination,
                'shop' => $shop,
                'imagenes' => $imagenes,
            ]);
        } catch (Exception $e) {
            Log::error("Error en la función ordenacio: {$e->getMessage()}");


            return redirect()->route('error.generic');
        }
    }

    public function tipo(Request $request, string $nombre)
    {
        try {
            $shop = User::where('shop_url', '=', $nombre)
                ->first();

            $id = $shop->id;

            $busqueda = $request->all();
            $id_busqueda =  session('id_busquedaTienda');
            $id_categoria = session('id_categoriaTienda');
            $id_ordenacion = session('id_ordenacionTienda');
            $id_tipo = $busqueda['tipoSeleccion'] ?? '';

            session(["id_tipoTienda" => $id_tipo]);

            $session_tipo = session('id_tipoTienda');
            $productos = Producto::mostrarProductosFiltrados($id_busqueda, $id_categoria, $id_ordenacion, $id, $session_tipo)
                ->paginate(intval(env('PAGINATE_NUM')));

            $imagenes = HomeController::peticionUrlImageProduct($productos, '/api/obtenerImagePrincipal');

            $pagination =  $productos->appends([
                'search' => $id_busqueda,
                'categorySelect' => $id_categoria,
                'ordenSelect' => $id_ordenacion,
                'tipoSeleccion' => $session_tipo
            ]);

            $categorias = Categoria::mostrarCategorias();
            $filtroTienda = [$id_busqueda, $id_categoria, $id_ordenacion, $session_tipo];
            $shop = User::where('id', '=', $id)
                ->first();
            Log::channel('desarrollo')->info('La funció tipo de ShopController funciona correctament');
            return view('tienda.tienda', compact('productos', 'pagination', 'shop', 'categorias', 'filtroTienda', 'imagenes'));
        } catch (Exception $e) {
            Log::error("Error en la función tipo: {$e->getMessage()}");


            return redirect()->route('error.generic');
        }
    }
}