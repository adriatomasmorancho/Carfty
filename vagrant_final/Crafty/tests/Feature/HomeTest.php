<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_No_Hay_Productos_Home(): void
    {
        $response = $this->get('/home/0');

        $response->assertSeeText('No hay productos');
    }

    public function test_Si_Hay_Productos_Home(): void
    {

        $this->crearTags();

        $this->crearUsuario();

        $this->crearProductos();

        $this->crearUsuarioProducto();

        $this->crearTagProducto();

        $response = $this->get('/home/0');

        $response->assertDontSeeText('No hay productos');
    }

    public function test_Paginate_Home(): void
    {
       
        $this->crearTags();

        $this->crearUsuario();
        
        $productos = $this->crearProductos();

        $this->crearUsuarioProducto();

        $this->crearTagProducto();

        $response = $this->get('/home/0');

        $response->assertSee($productos[0]->nombre_producto);
        $response->assertDontSee($productos[1]->nombre_producto);
    }

    public function test_Buscador_Hay_productos()
    {
        $this->crearUsuario();

        $productos = $this->crearProductos();

        $this->crearUsuarioProducto();

        $this->crearCategorias();

        $this->crearCategoriaProducto();

        $this->crearTags();

        $this->crearTagProducto();

        $response = $this->get('/productos');

        $response->assertSee($productos[0]->nombre_producto);
    }

    public function test_Buscador_No_Hay_productos()
    {
        $this->crearCategorias();

        $this->crearTags();

        $response = $this->get('/productos');

        $response->assertSeeText('No hay productos');
    }

    public function test_Categoria_Hay_productos()
    {
        $this->crearUsuario();

        $productos = $this->crearProductos();

        $this->crearUsuarioProducto();

        $this->crearCategorias();

        $this->crearCategoriaProducto();

        $this->crearTags();

        $this->crearTagProducto();

        $response = $this->get('/categoria');

        $response->assertSee($productos[0]->nombre_producto);
    }

    public function test_Categoria_No_Hay_productos()
    {
        $this->crearCategorias();

        $this->crearTags();

        $response = $this->get('/categoria');

        $response->assertSeeText('No hay productos');
    }

    public function test_Ordenacion_Hay_productos()
    {
        $this->crearUsuario();

        $productos = $this->crearProductos();

        $this->crearUsuarioProducto();

        $this->crearCategorias();

        $this->crearCategoriaProducto();

        $this->crearTags();

        $this->crearTagProducto();

        $response = $this->get('/filter');

        $response->assertSee($productos[0]->nombre_producto);
    }

    public function test_Ordenacion_No_Hay_productos()
    {
        $this->crearCategorias();

        $this->crearTags();

        $response = $this->get('/filter');

        $response->assertSeeText('No hay productos');
    }

    public static function crearProductos()
    {

        $productosFactory = Producto::factory(2)->create();
        $url = "https://loremflickr.com/640/480/technics";

        foreach ($productosFactory as $producto) {
            $arrayImagen = [];

            $arrayImagen[] = [
                'name' => 'imagePrincipal',
                'contents' => file_get_contents($url),
                'filename' => uniqid('product_', true) . '.png'
            ];
    
            for ($i = 0; $i < 3; $i++) {
                $arrayImagen[] = [
                    'name' => 'image[' . $i . ']',
                    'contents' => file_get_contents($url),
                    'filename' => uniqid('product_', true) . '.png'
                ];
            }

            $arrayImagen[] = [
                'name' => 'producto_id',
                'contents' => $producto->id
            ];

            $urlApi = env('URL_API', 'http://127.0.0.1%27/');
            $response = Http::attach($arrayImagen)->post($urlApi . '/api/addImage');
        }
        
       return $productosFactory;
    }

    public static function crearUsuario()
    {
        $usuario = [
                'name' => 'Adria',
                'email' =>  'adria@gmail.com',
                'password' => Hash::make('123456'),
                'shop' => 1,
                'shop_name' => 'Adria SL',
                'shop_url' => 'Adria-SL'
        ];
        
            $b = new User();
            $b->name = $usuario['name'];
            $b->email = $usuario['email'];
            $b->password = $usuario['password'];
            $b->shop = $usuario['shop'];
            $b->shop_name = $usuario['shop_name'];
            $b->shop_url = $usuario['shop_url'];
            $b->save();

        DB::table('users')->where('id', 1)->update(['verify_send' => 1]);
        
    }

    public static function crearUsuarioProducto()
    {
        $productos = DB::table('productos')->get();

        foreach ($productos as $product) {

            $shop = [
                [
                    'usuario_id' => 1,
                    'producto_id' => $product->id
                ]
            ];

            DB::table('shop')->insert($shop);
        }
        
    }

    public static function crearCategorias()
    {
        return Categoria::factory(8)->create();
    }

    public static function crearTags()
    {
        return Tag::factory(8)->create();
    }

    public static function crearCategoriaProducto()
    {
        $productos = DB::table('productos')->get();

        foreach ($productos as $product) {

            $categoria = DB::table('categorias')->inRandomOrder()->first();

            $categoria_producto = [
                [
                    'categoria_id' => $categoria->id,
                    'producto_id' => $product->id
                ]
            ];

            DB::table('categorias_productos')->insert($categoria_producto);
        }
    }

    public static function crearTagProducto()
    {
        $productos = DB::table('productos')->get();

        foreach ($productos as $product) {

            $tag = DB::table('tags')->inRandomOrder()->first();

            $tag_producto = [
                [
                    'tag_id' => $tag->id,
                    'producto_id' => $product->id
                ]
            ];

            DB::table('tags_productos')->insert($tag_producto);
        }
    }
}
