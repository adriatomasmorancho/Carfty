<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetallePedidoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    
    public function test_Mostrar_Productos_Detalle_Pedido(): void
    {
        $this->crearUsuarioAuth();

        $this->crearPedidoUsuarioNoAuth();

        $producto = $this->crearProductos();

        $this->crearPedido();

        $this->crearLineaPedidos();

        $response = $this->get('/detalle_pedido/1');

        $response->assertSee($producto[0]->nombre_producto);
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

    public static function crearPedidoUsuarioNoAuth()
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
    
    }

    public static function crearPedido(){
        $comandas_final = [
            'comprador_id' => 1,
            'vendedor_id' => 2,
            'productos' => '[]',
            'precio_pedido' => 7,
            'status' => 'Pendiente',
            'created_at' => now(),
        ];

        DB::table('pedidos')->insert($comandas_final);
        
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

    public static function crearLineaPedidos()
    {
       
        $lineaPedido = [
            'pedido_id' => 1,
            'producto_id' => 1,
        ];
        

        DB::table('linea_pedidos')->insert($lineaPedido);
        
    }
}
