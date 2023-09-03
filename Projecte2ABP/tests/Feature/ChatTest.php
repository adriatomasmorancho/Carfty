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

class ChatTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_Mostrar_Xat_Usuario_Auth(): void
    {

        $this->crearUsuarioAuth();

        $this->crearPedidoUsuarioNoAuth();

        $this->crearPedido();

        $this->crearChat();

        $this->crearChatUsers();

        $response = $this->get('/chat');

        $response->assertSeeText('Joan');
    }

    public function test_Mostrar_Mensaje_Chat(): void
    {

        $this->crearUsuarioAuth();

        $this->crearPedidoUsuarioNoAuth();

        $this->crearPedido();

        $this->crearChat();

        $this->crearChatUsers();

        $this->crearMensaje();

        $response = $this->get('/mostrar_chat/1');

        $response->assertSeeText('Hola');
    }

    public function test_Enviar_Mensaje_Chat(): void
    {

        $this->crearUsuarioAuth();

        $this->crearPedidoUsuarioNoAuth();

        $this->crearPedido();

        $this->crearChat();

        $this->crearChatUsers();

        $this->crearMensaje();

        $data=[
            'mensaje' => 'Adeu',
            'id' => 1
        ];

        $response = $this->post('/enviar_mensaje', $data);

        $response->assertSeeText('Adeu');
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

    public static function crearChat()
    {
       
        $chat = [
            'pedido_id' => 1,
        ];

        DB::table('chats')->insert($chat);
        
    }

    public static function crearChatUsers()
    {
       
        $chat = [
            'chat_id' => 1,
            'usuario_id' => 1,
        ];

        DB::table('chat_users')->insert($chat);
        
    }

    public static function crearMensaje()
    {
       
        $mensaje = [
            'chat_id' => 1,
            'mensaje' => 'Hola',
            'usuario_id' => 1,
            'visto' => 1,
            'mensajes_automaticos_usuario_id'=>1,
        ];

        DB::table('mensajes')->insert($mensaje);
        
    }

    
}

