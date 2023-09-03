<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Tag;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tags_seleccionados = json_decode(getenv('TAGS'));

            $tags = Tag::mostrarTagsSeleccionados($tags_seleccionados);

            $productos = [];
            $imagenes = [];

            foreach ($tags as $tag) {
                $productos[$tag->nombre_tag] =  Producto::mostrarProductosLanding($tag->nombre_tag);
                $imagenes[$tag->nombre_tag] = HomeController::peticionUrlImageProduct($productos[$tag->nombre_tag], '/api/obtenerImagePrincipal');
            }
            Log::channel('desarrollo')->info('La funció index de LandingPageController funciona correctament');
            return view('landing.landing', ['productos' => $productos, 'tags' => $tags, 'imagenes' => $imagenes]);
        } catch (Exception $e) {
            Log::error("Error en la función index de LandingPageController: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}