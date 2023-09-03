<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TiendaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_Usuario_Puede_Ver_Productos_En_Su_Tienda(): void
    {
        
        $this->crearCategorias();

        $this->crearUsuarioAuth();

        $producto=$this->crearProductos();

        $this->crearUsuarioProducto();

        $response = $this->get('/shop/Adria-SL');

        $response->assertSee($producto[0]->nombre_producto);

        $response->assertSeeText('Crear');
    }

    public function test_Usuario_Puede_Ver_Productos_Tienda_Que_No_Sea_Suya(): void
    {
        
        $this->crearCategorias();

        $this->crearUsuarioAuth();

        $producto=$this->crearProductos();

        $this->crearUsuarioProducto();

        $this->crearUsuarioQueEntraEnTienaQueNoEsSuya();

        $response = $this->get('/shop/Adria-SL');

        $response->assertSee($producto[0]->nombre_producto);

        $response->assertDontSeeText('Crear');
    }

    public function test_Tienda_Sin_Productos(): void
    {
        
        $this->crearCategorias();

        $this->crearUsuarioAuth();

        $response = $this->get('/shop/Adria-SL');

        $response->assertSeeText('No hay productos');
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

    public static function crearUsuarioQueEntraEnTienaQueNoEsSuya()
    {

        $usuario = [
            'name' => 'Joan',
            'email' =>  'joan@gmail.com',
            'password' => Hash::make('123456'),
            'shop' => 1,
            'shop_name' => 'Joan SL',
            'shop_url' => 'Joan-SL'
    ];
    
        $b = new User();
        $b->name = $usuario['name'];
        $b->email = $usuario['email'];
        $b->password = $usuario['password'];
        $b->shop = $usuario['shop'];
        $b->shop_name = $usuario['shop_name'];
        $b->shop_url = $usuario['shop_url'];
        $b->save();

    DB::table('users')->where('id', 2)->update(['verify_send' => 1]);

    Auth::login($b);
    
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

    public static function crearCategorias()
    {
        return Categoria::factory(8)->create();
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


}
