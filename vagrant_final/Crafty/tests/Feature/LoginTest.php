<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testLogin()
    {


        $user = User::create([
            'name' => 'joan',
            'email' => 'joan@gmail.com',
            'password' => Hash::make('Copernic123')
        ]);


        $user->save();


        Auth::login($user);

        $this->assertTrue(Auth::check());
    }


    public function testFormatoDeEmailValidoLogin()
    {


        $user = [
            'name' => 'PouPere2',
            'email' => 'popere@gmail.com',
            'password' => Hash::make('Copernic123')
        ];


        $validator = Validator::make($user, [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ]);

        $this->assertTrue($validator->passes(), $validator->errors());
    }

    public function testFormatoDeEmailInvalidoLogin()
    {


        $user = [
            'name' => 'PouPere2',
            'email' => 'poperegmail.com',
            'password' => Hash::make('Copernic123')
        ];


        $validator = Validator::make($user, [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ]);

        $this->assertTrue($validator->fails());
    }

    public function testFormatoPasswordLogin()
    {
        $user = [
            'name' => 'PerePou',
            'email' => 'perepou@gmail.com',
            'password' => Hash::make('Copernic123')
        ];

        $pattern = '/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/';

        $this->assertMatchesRegularExpression($pattern, $user['password']);
    }
}