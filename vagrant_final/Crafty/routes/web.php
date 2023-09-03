<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ErrorsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LandingPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LandingPageController::class, 'index'])->name('landing.index');

Route::get('/home/{id}', [HomeController::class, 'index'])->name('home.index');

Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'login'])->name('login.login');
Route::get('/log-out', [LoginController::class, 'logout'])->name('login.logout');

Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/validar-register', [RegisterController::class, 'register'])->name('registro.registro');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'cartProducts'])->name('cart.index');

Route::get('/logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/productos/{id}',  [HomeController::class, 'show'])->where(['id' => '[0-9]+'])->name('productos.show');
Route::get('/productos/crear',  [ShopController::class, 'create'])->name('productos.crear');
Route::post('/productos/store',  [ShopController::class, 'store'])->name('productos.store');
Route::get('/productos/editar/{id}',  [ShopController::class, 'edit'])->name('productos.editar');
Route::post('/productos/guardar',  [ShopController::class, 'update'])->name('productos.guardar');
Route::get('/productos/eliminar/{id}',  [ShopController::class, 'destroy'])->name('productos.eliminar');
Route::get('/productos',  [HomeController::class, 'search'])->name('productos.search');

Route::get('/filter',  [HomeController::class, 'ordenacio'])->name('productos.filter');

Route::get('/categoria',  [HomeController::class, 'categoria'])->name('productos.categoria');

Route::get('/editarUsuario', [UserController::class, 'edit'])->name('user.editar');
Route::post('/guardarUsuario', [UserController::class, 'update'])->name('user.guardar');

Route::get('/shop/{nombre}', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{nombre}/search',  [ShopController::class, 'search'])->name('shop.search');
Route::get('/shop/{nombre}/filter',  [ShopController::class, 'ordenacio'])->name('shop.filter');
Route::get('/shop/{nombre}/categoria',  [ShopController::class, 'categoria'])->name('shop.categoria');
Route::get('/shop/{nombre}/type',  [ShopController::class, 'tipo'])->name('shop.productos');

Route::get('/rememberPassword', [LoginController::class, 'rememberPassword'])->name('login.rememberPassword');
Route::get('/verify_email/{verification_code}', [RegisterController::class, 'verify_email'])->name('verify_email');
Route::get('/newPassword/{userM}', [LoginController::class, 'newPassword'])->name('newPassword');
Route::post('/updatePasswordValue', [LoginController::class, 'updatePasswordValue'])->name('updatePasswordValue');
Route::post('/validateEmail', [LoginController::class, 'validateEmail'])->name('login.validateEmail');



Route::get('/error-generico', [ErrorsController::class, 'viewErrorGeneric'])->name('error.generic');
Route::get('/error-producto', [ErrorsController::class, 'viewErrorProduct'])->name('error.product');
Route::get('/error-shop', [ErrorsController::class, 'viewErrorShop'])->name('error.shop');


Route::post('/tramitar_pedido', [CartController::class, 'tramitar_pedido'])->name('comanda.index');

Route::get('/historial_compras', [CartController::class, 'historial_compras'])->name('compras.index');

Route::get('/historial_ventas', [CartController::class, 'historial_ventas'])->name('ventas.index');

Route::get('/pago_aceptado/{id}', [CartController::class, 'pago_realizado'])->name('pago.index');

Route::get('/envio_realizado/{id}', [CartController::class, 'envio_realizado'])->name('enviado.index');

Route::get('/envio_recibido/{id}', [CartController::class, 'envio_recibido'])->name('recibido.index');

Route::get('/pedido_rechazado/{id}', [CartController::class, 'pedido_rechazado'])->name('rechazado.index');

Route::get('/pedido_finalizado/{id}', [CartController::class, 'pedido_finalizado'])->name('finalizado.index');

Route::get('/compras/filter', [CartController::class, 'compras_ordenacion'])->name('compras.filter');

Route::get('/compras/estado', [CartController::class, 'compras_estado'])->name('compras.estado');

Route::get('/ventas/filter', [CartController::class, 'ventas_ordenacion'])->name('ventas.filter');

Route::get('/ventas/estado', [CartController::class, 'ventas_estado'])->name('ventas.estado');

Route::get('/detalle_pedido/{id}', [CartController::class, 'detalle_pedido'])->name('pedido.detalle');

Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

Route::get('/mostrar_chat/{id}', [ChatController::class, 'mostrar_chat'])->name('mostrar_chat.index');

Route::post('/enviar_mensaje', [ChatController::class, 'enviar_mensaje'])->name('enviar_mensaje.index');

Route::post('/generatePDF/pdf', [PdfController::class, 'generatePDF'])->name('generatePDF');



