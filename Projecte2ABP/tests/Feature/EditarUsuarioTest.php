<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditarUsuarioTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_Editar_Usuario(): void
    {
        $data=[
            'name' => 'Joan',
            'email' =>  'joan@gmail.com',
        ];

        $response = $this->post('/guardarUsuario', $data);

        $response = $this->get('/editarUsuario');

        $response->assertSeeText('Joan');
    }

    public static function crearUsuarioAuth()
    {
        $usuario = [
                'name' => 'Adria',
                'email' =>  'adria@gmail.com',
                'password' => Hash::make('123456')
        ];
        
            $b = new User();
            $b->name = $usuario['name'];
            $b->email = $usuario['email'];
            $b->password = $usuario['password'];
            $b->save();

        DB::table('users')->where('id', 1)->update(['verify_send' => 1]);

        Auth::login($b);
        
    }
}

