<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\EmailVerify;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\BienvenidaMailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    public function index()
    {
        Log::channel('desarrollo')->info('Se ha accedido al cuestionario de registro');
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|alpha_dash|unique:users',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $request->all();
            $carrito = $data['carrito'] ?? [];

            if ($data['carrito'] == null) {
                $carrito = [];
            }

            $token = Str::random(30);

            // Longitud del token
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $token = substr(str_shuffle($alphabet), 0, 30);

            $registerUser = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                // 'password' => $token = preg_replace("/[^a-zA-Z]/", "", $hashedPassword),
                'carrito' => $carrito,
                'verification_code' => $token,
            ]);
            try {
                Log::channel('desarrollo')->info('Email de registro enviado correctamente.');
                Mail::to('abpmarketplacecrafty@gmail.com')->send(new EmailVerify($registerUser));
            } catch (Exception $e) {
                $registerUser->delete();
                Log::error("Error al enviar email al usuario: " . $data['name'] . ", Mensaje de error: " . $e->getMessage());
                return redirect()->route('error.generic');
            }



            Log::channel('desarrollo')->info('Usuario registrado');



            return response()->json(['message' => 'Se ha registrado correctamente el usuario ' . $data['name'] .
                " Debe verificar el correo electronico antes de poder iniciar sesión."]);
        } catch (Exception $e) {
            Log::error("Error al registrar - Usuario: " . $data['name'] . ", Mensaje de error: " . $e->getMessage());
            return redirect()->route('error.generic');
        }
    }



    public function verify_email(string $verification_code)
    {
        $userVerify = User::where('verification_code', $verification_code)->first();


        if (!$userVerify) {


            return redirect()->route('register.index')->with('error', 'URL inválida');
        } else {

            if ($userVerify->verify_send) {
                Log::channel('desarrollo')->info('Invitación ja enviada');
                return redirect()->route('register.index');
            } else {
                $userVerify->update(['verify_send' => 1]);
                Log::channel('desarrollo')->info('Te has unido a crafty');
                return redirect()->route('login.index');
            }
        }
    }
}