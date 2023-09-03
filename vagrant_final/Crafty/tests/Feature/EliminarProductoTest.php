<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EliminarProductoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_Eliminar_Producto(): void
    {
        $this->crearTags();

        $this->crearUsuarioAuth();

        $this->crearProductos();

        $this->crearUsuarioProducto();

        $this->crearTagProducto();

        $this->get('/productos/eliminar/1');

        $response = $this->get('/home/0');

        $response->assertSeeText('No hay productos');

        
    }


    public static function crearProductos()
    {

        $productosFactory = Producto::factory(1)->create();
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

    public static function crearUsuarioAuth()
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

        Auth::login($b);
        
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
    public static function crearTags()
    {
        return Tag::factory(8)->create();
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
