<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Tag;
use App\Models\User;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function edit()
    {
        try {
            $user = auth()->user();

            Log::channel('desarrollo')->info('Vista Editar Usuario');
            return view('user.edit', ['usuario' => $user]);
        } catch (Exception $e) {
            Log::error("Error en la función edit: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $user = auth()->user();

            $rules = [
                'password' => 'nullable|min:6',
                'newPassword' => 'nullable|min:6',
                'repeatNewPassword' => 'nullable|min:6|same:newPassword',
            ];

            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore(auth()->user()->id),
            ];

            $rules['name'] = [
                'required',
                'string',
                Rule::unique('users', 'name')->ignore(auth()->user()->id),
            ];

            if ($request['shop'] == 1) {
                $rules['shop_name'] = [
                    'required',
                    'string',
                    'regex:/^[A-Za-z0-9\s]+$/',
                    Rule::unique('users', 'shop_name')->ignore(auth()->user()->id),
                ];
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            if ($request['password'] != null && !(Hash::check($request['password'], $user->password))) {
                return redirect()
                    ->back()
                    ->withErrors(["password" => "No coincide la contraseña"])
                    ->withInput();
            }

            if ($request['shop'] == 1) {
                $user->shop = $request['shop'];
                $user->shop_name = $request['shop_name'];
                $user->shop_banner = $request['shop_banner'];
                $user->shop_url = str_replace(' ', '-', $request['shop_name']);

                $shop_image = $request->file('shop_image');

                if ($shop_image) {
                    $imageName = $request['shop_name'] . '.png';
                    $shop_image->storeAs('public', $imageName);
                    $url = Storage::url($imageName);
                    $user->shop_image = $url;
                }
            }

            $name = $request['name'];
            $email = $request['email'];

            if ($request['password'] != null && $request['newPassword'] == null) {
                return redirect()
                    ->back()
                    ->withErrors(["newPassword" => "El campo nueva contraseña no puede estar vacio"])
                    ->withInput();
            }

            if ($request['newPassword'] != null) {
                $password = Hash::make($request['newPassword']);
                $user->password = $password;
            }

            $image = $request->file('image');

            if ($image) {
                $imageName = $name . '.png';
                $image->storeAs('public', $imageName);
                $url = Storage::url($imageName);
                $user->image = $url;
            }

            $user->name = $name;
            $user->email = $email;
            $user->save();
            Log::channel('desarrollo')->info('La funció update de UserController funciona correctament');
            return redirect()->back()->with('success', 'Los datos se han actualizado correctamente');
        } catch (Exception $e) {
            Log::error("Error en la función update: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}