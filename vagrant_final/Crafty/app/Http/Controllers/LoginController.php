<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Producto;
use App\Mail\EmailPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\EmailToChangePassword;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }



    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $data = $request->all();
        $carrito = $data['carrito'] ?? [];


        Log::channel('credenciales')->info($request);


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Log::channel('desarrollo')->info('Usuario logueado');
            $userVerify = User::where('email', '=', $data['email'])->first();

            $user = auth()->user();

            if ($userVerify->verify_send != 0) {
                if ($user->carrito == "[]") {
                    $user->carrito = $carrito;
                    $carritoArray = json_decode($carrito);

                    $productos = Producto::join("shop","shop.producto_id","=","productos.id")
                    ->whereIn('productos.id', $carritoArray)
                    ->where("shop.usuario_id","!=",$user->id)
                    ->get();

                    $arrayProductosVerificados = [];

                    foreach ($productos as $product) {
                        $arrayProductosVerificados[] = $product->producto_id;
                    }

                    $user->carrito = json_encode($arrayProductosVerificados);
                    $user->save();
                } else {

                    $productos = Producto::whereIn('id', json_decode($user->carrito))->get();

                    $arrayProductosVerificados = [];

                    foreach ($productos as $product) {
                        $arrayProductosVerificados[] = $product->id;
                    }

                    $user->carrito = json_encode($arrayProductosVerificados);
                    $user->save();

                    $carrito = json_decode($user->carrito);
                }

                $carrito = json_decode($user->carrito);

                return response()->json($carrito);
            } else {
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                } else {

                    session()->flush();
                    Auth::logout();
                    return response()->json(['errorVerify' => 'Antes de poder iniciar verifica el correo.']);
                }
            }
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            return response()->json(['error' => 'Las credenciales proporcionadas son incorrectas.']);
        }
    }

    public function logout()
    {
        session()->flush();
        Auth::logout();

        return redirect(route('landing.index'));
    }

    public function rememberPassword()
    {
        return view('auth.rememberPassword');
    }


    public function validateEmail(Request $request)
    {


        $emailTry = $request->input('emailPass');



        $queryId = DB::table('users')
            ->select('id')->where('email', $emailTry)
            ->first();

        if ($queryId) {

            $userEmail = User::find($queryId->id);


            Mail::to('abpmarketplacecrafty@gmail.com')->send(new EmailPassword($userEmail));
            return redirect()->route('login.rememberPassword')->with('messageEmailVerify', 'Se le ha enviado un correo para poder restablecer su contraseña. Por favor, revise su bandeja de entrada o spam y de click al botón Restablecer contraseña.  ');
        } else {


            return redirect()->route('login.rememberPassword')->with('messageEmailError', 'El email introducido no se encuentra registrado en la BBDD.');
        }
    }


    public function newPassword($userM)
    {
        $userEmail = User::find($userM);

        return view('auth.newPassword', ['userEmail' => $userEmail]);
    }



    public function updatePasswordValue(Request $request)
    {


        $request->validate([
            'currentPassword' => 'required|string',
            'changePassword' => 'required|string',
            'changePassword2' => 'required|string'
        ]);



        if ($request['changePassword'] == $request['changePassword2']) {
            $userChangePassword = User::where('password', $request['currentPassword'])->first();



            $hashedPassword = Hash::make($request['changePassword']);

            $userChangePassword->update(['password' => $hashedPassword]);
            return view('auth.login');
        }
    }
}