<?php

namespace App\Http\Controllers;

use Faker\Factory;
use Faker\Generator;
use App\Models\Image;

use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Container\Container;
//use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

// require_once 'vendor/autoload.php';
class ApiImageController extends Controller
{




    public function addImage(Request $request)
    {

        if ($request->has('image')) {

            $producto_id = $request->input('producto_id');
            $images = $request->all();
            $imagePrincipal = $request->file('imagePrincipal');

            if (!File::exists(public_path('storage/images'))) {
                File::makeDirectory(public_path('storage/images'), 0777, true);
            }

            $filenamePrincipal = uniqid() . '_' . $imagePrincipal->getClientOriginalName();
            Storage::disk('public_images')->put($filenamePrincipal, file_get_contents($imagePrincipal));
            $imageSave = new Image();
            $imageSave->name = $filenamePrincipal;
            $imageSave->url = '/storage/images/' . $filenamePrincipal;
            $imageSave->product_id = $producto_id;
            $imageSave->imagePrincipal = 1;
            $imageSave->save();

            $i = 2;
            foreach ($images['image'] as $value) {

                $filename = uniqid() . '_' . $value->getClientOriginalName();
                Storage::disk('public_images')->put($filename, file_get_contents($value));

                //return response()->json($value->getMimeType(), 201);

                $imageSave = new Image();
                $imageSave->name = $filename;
                $imageSave->url = '/storage/images/' . $filename;
                $imageSave->product_id = $producto_id;
                $imageSave->imagePrincipal = $i;
                $imageSave->save();

                $i++;
            }
            return response()->json(['message' => 'Imagen guardada correctamente'], 201);
        } else {
            return response()->json(['error' => 'No se encontraron imágenes'], 401);
        }
    }

    public function editImage(Request $request)
    {

        if ($request->has('image') || $request->has('imagePrincipal')) {

            $producto_id = $request->input('producto_id');
            $images = $request->all();
            $imagePrincipal = $request->file('imagePrincipal');

            if ($request->has('imagePrincipal')) {
                // return response()->json(['pasa por aqui'], 201);
                $filenamePrincipal = uniqid() . '_' . $imagePrincipal->getClientOriginalName();
                Storage::disk('public_images')->put($filenamePrincipal, file_get_contents($imagePrincipal));
                $imageSave = Image::where('product_id','=', $producto_id)->where('imagePrincipal','=', 1)->first();
                // return response()->json([$imageSave], 201);
                $imageSave->name = $filenamePrincipal;
                $imageSave->url = '/storage/images/' . $filenamePrincipal;
                $imageSave->save();
            }

            if ($request->has('image')) {
                foreach ($images['image'] as $key => $value) {
                    if ($key == 0) {
                        $imageSave = Image::where('product_id','=', $producto_id)->where('imagePrincipal','=', 2)->first();
                        Storage::delete($imageSave->url);
                    }

                    if ($key == 1) {
                        $imageSave = Image::where('product_id','=', $producto_id)->where('imagePrincipal','=', 3)->first();
                        Storage::delete($imageSave->url);
                    }

                    if ($key == 2) {
                        $imageSave = Image::where('product_id','=', $producto_id)->where('imagePrincipal', '=', 4)->first();
                        Storage::delete($imageSave->url);
                    }

                    $filename = uniqid() . '_' . $value->getClientOriginalName();
                    Storage::disk('public_images')->put($filename, file_get_contents($value));
                    $imageSave->name = $filename;
                    $imageSave->url = '/storage/images/' . $filename;
                    $imageSave->save();
                }
            }

            return response()->json(['message' => 'Imagen guardada correctamente'], 201);
        } else {
            return response()->json(['error' => 'No se encontraron imágenes'], 401);
        }
    }

    public function obtenerImagePrincipal(Request $request)
    {
        $productos = $request->json('productos');

        $productoIds = array_column($productos, 'id');


        $imagenes = Image::whereIn('product_id', $productoIds)
            ->where('imagePrincipal', 1)
            ->get();

        return response()->json(['imagenes' => $imagenes], 201);
    }

    public function obtenerImagesProduct(Request $request)
    {
        $productos = $request->json('productos');

        $productoIds = array_column($productos, 'id');

        $imagenes = Image::whereIn('product_id', $productoIds)->get();

        return response()->json(['imagenes' => $imagenes], 201);
    }

    public function eliminarImagesProduct(Request $request)
    {
        $productos = $request->json('productos');

        $productoIds = array_column($productos, 'id');

        $imagenes = Image::whereIn('product_id', $productoIds)->get();

        foreach ($imagenes as $imagen) {
            Storage::delete($imagen->url);
        }

        $imagenes = Image::whereIn('product_id', $productoIds)->delete();

        return response()->json(['message' => 'Se han eliminado correctamente'], 201);
    }

    public function generateImages()
    {

        //$path = public_path('storage/imatgesGenerades/');

        $path = storage_path() . '/app/public/imatgesGenerades/';
        $files = scandir($path);
        //$files = Storage::allFiles($path);


        $fileNames = [];

        foreach ($files as $file) {

            if ($file != '.' or $file != '..') {
                $fileNames[] = $file;
            }
        }


        $json = json_encode($fileNames);

        config(['app.arrayImages' => $fileNames]);

        $arr = config('app.arrayImages');

        Log::info($arr);
        return response()->json([
            'array' => $arr,
        ], 201);
    }

    public function deleteImage($value)
    {

        $path = storage_path() . '/app/public/imatgesGenerades';
        $files = scandir($path);

        $fileNames = [];

        foreach ($files as $file) {


            $fileNames[] = $file;
        }

        config(['app.arrayImages' => $fileNames]);

        // $json = json_encode($fileNames);

        $array = config('app.arrayImages');


        Log::info($array);
        // $value = (int)$value;


        File::delete($path . '/' . $array[2]);
        unset($array[2]);
        config(['app.arrayImages' => $array]);

        return response()->json([
            'array' => $array,
        ], 201);
    }
}
