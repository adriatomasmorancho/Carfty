<?php

use App\Models\News;
use App\Models\Image;

use App\Models\Imagen;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ApiImageController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/news', [NewsController::class, 'index']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('image', function () {
    return Image::all();
});

Route::get('/generateImages', [ApiImageController::class, 'generateImages'])->name('generateImages');


Route::post('/addImage', [ApiImageController::class, 'addImage'])->name('addImage');
Route::post('/editImage', [ApiImageController::class, 'editImage'])->name('editImage');
Route::post('/obtenerImagePrincipal', [ApiImageController::class, 'obtenerImagePrincipal'])->name('obtenerImagePrincipal')
->withoutMiddleware("throttle:api")->middleware("throttle:300:1");;
Route::post('/obtenerImagesProduct', [ApiImageController::class, 'obtenerImagesProduct'])->name('obtenerImagesProduct')
->withoutMiddleware("throttle:api")->middleware("throttle:300:1");;
Route::post('/eliminarImagesProduct', [ApiImageController::class, 'eliminarImagesProduct'])->name('eliminarImagesProduct');

Route::get('/deleteImage/{id}', [ApiImageController::class, 'deleteImage'])->name('deleteImage');
