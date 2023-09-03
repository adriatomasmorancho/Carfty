<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
   /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function testRegistroDeUsuario()
    {
        $user = User::create([
            'name' => 'PouPere',
            'email' => 'poupere@gmail.com',
            'password' => Hash::make('Copernic123')
        ]);


        $user->save();

        $result = User::where('email', '=', 'poupere@gmail.com')->first();

        $this->assertEquals($user['name'], $result->name);
        $this->assertEquals($user['email'], $result->email);
        $this->assertTrue(Hash::check('Copernic123', $result->password));
    }

    public function testFormatoDeEmailValido()
    {

        $userData = [
            'name' => 'PouPere',
            'email' => 'poupere@gmail.com',
            'password' => Hash::make('Copernic123')
        ];


        $user = User::create($userData);




        $validator = Validator::make($userData, [
            'email' => 'required|email'
        ]);


        if ($validator->passes()) {
            $user->save();
            $this->assertFalse($validator->fails());
        }
    }

    public function testFormatoDeEmailInvalido()
    {

        $userData = [
            'name' => 'PouPere',
            'email' => 'pouperegmail.com',
            'password' => Hash::make('Copernic123')
        ];


        $user = User::create($userData);


        $validator = Validator::make($userData, [
            'email' => 'required|email'
        ]);


        $this->assertTrue($validator->fails());
    }



    public function testFormatoPassword()
    {
        $user = [
            'name' => 'PouPere',
            'email' => 'poupere@gmail.com',
            'password' => Hash::make('Copernic123')
        ];

        $pattern = '/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/';

        $this->assertMatchesRegularExpression($pattern, $user['password']);
    }
}
