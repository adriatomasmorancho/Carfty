<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComprasTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_Hay_Pedidos_En_Compras(): void
    {
        $this->crearUsuarioAuth();

        $this->crearPedidoUsuarioAuth();

        $response = $this->get('/historial_compras');

        $response->assertDontSeeText('No hay pedidos');
    }

    public function test_No_Hay_Pedidos_En_Compras(): void
    {
        $this->crearUsuarioAuth();

        $this->crearPedidoUsuarioNoAuth();

        $response = $this->get('/historial_compras');

        $response->assertSeeText('No hay pedidos');
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

    public static function crearPedidoUsuarioAuth()
    {
       
        $comandas_final = [
            'comprador_id' => 1,
            'vendedor_id' => 1,
            'productos' => '[]',
            'precio_pedido' => 7,
            'status' => 'Pendiente',
            'created_at' => now(),
        ];

        DB::table('pedidos')->insert($comandas_final);
        
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
       
        $comandas_final = [
            'comprador_id' => 2,
            'vendedor_id' => 2,
            'productos' => '[]',
            'precio_pedido' => 7,
            'status' => 'Pendiente',
            'created_at' => now(),
        ];

    DB::table('pedidos')->insert($comandas_final);
        
    }
}
