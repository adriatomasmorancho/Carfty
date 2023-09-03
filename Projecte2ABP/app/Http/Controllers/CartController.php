<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Chat;
use App\Models\Carrito;
use App\Models\Pedidos;
use App\Models\Producto;
use App\Models\LineaPedido;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('productos.cart');
    }

    /**
     * Show tall products of localStorage.
     */
    public function cartProducts(Request $request)
    {
        try {
            // Verifica si el usuario está autenticado
            if (auth()->check()) {
                $user = auth()->user();
                $carrito = json_decode($user->carrito);


                // Compara el carrito del usuario con el carrito del cliente
                $data = $request->all();
                $clienteCarrito = $data['carrito'] ?? [];
                if ($carrito != $clienteCarrito && $clienteCarrito != []) {
                    // Actualiza el carrito del usuario
                    $user->carrito = json_decode($clienteCarrito);
                    $user->save();

                    if (is_string($clienteCarrito)) {
                        $carrito = explode(",", str_replace(['[', ']'], '', $clienteCarrito));
                    }
                } else {
                    if (is_string($carrito)) {
                        $carrito = explode(",", str_replace(['[', ']'], '', $carrito));
                    }
                }


                $productos = Producto::join("shop", "shop.producto_id", "=", "productos.id")->whereIn('shop.producto_id', array_map('intval', $carrito))->get();
                $imagenes = HomeController::peticionUrlImageProduct($productos, '/api/obtenerImagePrincipal');

                $productos = Producto::whereIn('id', array_map('intval', $carrito))->get();

                // Muestra los productos en la vista
                return response()->json(['productos' => $productos, 'imagenes' => $imagenes, 'url_api' => env('URL_API')]);
            } else {

                $productos = Producto::join("shop", "shop.producto_id", "=", "productos.id")->whereIn('shop.producto_id', $request)->get();
                $imagenes = HomeController::peticionUrlImageProduct($productos, '/api/obtenerImagePrincipal');

                // Query de productos con el id del localStorage
                $productos = Producto::whereIn('id', $request)->get();
                // Devolver los productos como respuesta
                return response()->json(['productos' => $productos, 'imagenes' => $imagenes, 'url_api' => env('URL_API')]);
            }
        } catch (Exception $e) {
            Log::error("Error en la función cartProducts: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }

    public function tramitar_pedido(Request $request)
    {
        try {
            $productos = json_decode($request->carrito, true);

            $comandas = [];

            $comandas_ordenadas = [];


            foreach ($productos as  $value) {
                $producto = DB::table('shop')->join("productos", "shop.producto_id", "=", "productos.id")->where('producto_id', '=', $value)->first();
                if ($producto->vendido == 0) {
                    array_push($comandas, $producto);
                } else {
                    return response()->json(["errorTramitar" => "Error! Antes de poder tramitar tu pedido tienes que eliminar los productos que ya han sido vendidos"]);
                }
            }


            foreach ($comandas as $producto) {
                $id_vendedor = $producto->usuario_id;
                if (!isset($comandas_ordenadas[$id_vendedor])) {
                    $comandas_ordenadas[$id_vendedor] = array();
                }
                $comandas_ordenadas[$id_vendedor][] = $producto;
            }

            foreach ($comandas_ordenadas as $vendedor_id) {


                $array_productos = [];

                $precio = 0;

                foreach ($vendedor_id as $value) {
                    array_push($array_productos,  $value->producto_id);
                    $precio = $precio + $value->precio;
                }


                $comandas_final = [
                    'comprador_id' => auth()->user()->id,
                    'vendedor_id' => $vendedor_id[0]->usuario_id,
                    'productos' => json_encode($array_productos),
                    'precio_pedido' => $precio,
                    'status' => 'Pendiente',
                    'created_at' => now(),
                ];

                $pedido_id = DB::table('pedidos')->insertGetId($comandas_final);

                $chat = [
                    'pedido_id' => $pedido_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $chat_id = DB::table('chats')->insertGetId($chat);

                $chat_users1 = [
                    'chat_id' => $chat_id,
                    'usuario_id' => auth()->user()->id,
                ];
                $chat_users2 = [
                    'chat_id' => $chat_id,
                    'usuario_id' => $vendedor_id[0]->usuario_id,
                ];

                DB::table('chat_users')->insert($chat_users1);
                DB::table('chat_users')->insert($chat_users2);

                $mensaje1 = [
                    'chat_id' => $chat_id,
                    'mensaje' => 'Se ha comprado el producto',
                    'usuario_id' => 0,
                    'mensajes_automaticos_usuario_id' => auth()->user()->id,
                    'created_at' => now(),
                ];

                $mensaje2 = [
                    'chat_id' => $chat_id,
                    'mensaje' => 'Se ha comprado el producto',
                    'usuario_id' => 0,
                    'mensajes_automaticos_usuario_id' => $vendedor_id[0]->usuario_id,
                    'created_at' => now(),
                ];


                DB::table('mensajes')->insert($mensaje1);
                DB::table('mensajes')->insert($mensaje2);

                foreach ($array_productos as $value) {

                    $linea_comandas_final = [
                        'pedido_id' => $pedido_id,
                        'producto_id' => $value,
                    ];

                    DB::table('linea_pedidos')->insert($linea_comandas_final);
                }
            }


            foreach ($productos as  $value) {
                $producto = Producto::findOrFail($value);
                $producto->vendido = 1;
                $producto->save();
            }



            $user = auth()->user();
            $user->carrito = "[]";
            $user->save();

            return response()->json(["compraExitosa" => "Compra exitosa. Pendiente de confirmación"]);
        } catch (Exception $e) {
            Log::error("Error en la función tramitar_pedidos: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }


    public function historial_compras(Request $request)
    {
        try {
            $user = auth()->user()->id;

            session()->forget(['id_ventas_estado', 'id_ventas_ordenacion', 'id_compras_ordenacion', 'id_compras_estado']);

            $pedidos = Pedidos::pedidosComprador($user)->paginate(intval(env('PAGINATE_NUM')));

            $pagination = $pedidos->appends(request()->query());
            Log::channel('desarrollo')->info('La funció historial_compras  funciona correctament');

            return view('historial.compras', compact('pedidos', 'pagination'));
        } catch (Exception $e) {
            Log::error("Error en la función historial_compras: {$e->getMessage()}");
        }
    }

    public function historial_ventas(Request $request)
    {
        try {
            $user = auth()->user()->id;

            session()->forget(['id_ventas_estado', 'id_ventas_ordenacion', 'id_compras_ordenacion', 'id_compras_estado']);

            $pedidos = Pedidos::pedidosVendedor($user)->paginate(intval(env('PAGINATE_NUM')));

            $pagination = $pedidos->appends(request()->query());
            Log::channel('desarrollo')->info('La funció historial_ventas  funciona correctament');

            return view('historial.ventas', compact('pedidos', 'pagination'));
        } catch (Exception $e) {
            Log::error("Error en la función historial_ventas: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }

    public function pago_realizado(string $id)
    {
        try {
            DB::table('pedidos')
                ->where('id', '=', $id)
                ->update(['status' => 'Pago aceptado']);

            $pedidos = DB::table('pedidos')->where('id', $id)->first();

            $chat = DB::table('chats')->where('pedido_id', $id)->first();
            $chat_id = $chat->id;

            Chat::updateChat($id);

            $mensaje1 = [
                'chat_id' => $chat_id,
                'mensaje' => 'El pago ha sido aceptado',
                'usuario_id' => 0,
                'mensajes_automaticos_usuario_id' => $pedidos->comprador_id,
                'created_at' => now(),
            ];

            $mensaje2 = [
                'chat_id' => $chat_id,
                'mensaje' => 'El pago ha sido aceptado',
                'usuario_id' => 0,
                'mensajes_automaticos_usuario_id' =>  $pedidos->vendedor_id,
                'created_at' => now(),
            ];

            DB::table('mensajes')->insert($mensaje1);
            DB::table('mensajes')->insert($mensaje2);
            Log::channel('desarrollo')->info('La funció pago_realizado  funciona correctament');
            return redirect()->route('ventas.index');
        } catch (Exception $e) {
            Log::error("Error en la función pago_realizado: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }

    public function envio_realizado(string $id)
    {
        try {
            DB::table('pedidos')
                ->where('id', '=', $id)
                ->update(['status' => 'Envio hecho']);

            $pedidos = DB::table('pedidos')->where('id', $id)->first();

            $chat = DB::table('chats')->where('pedido_id', $id)->first();
            $chat_id = $chat->id;

            Chat::updateChat($id);

            $mensaje1 = [
                'chat_id' => $chat_id,
                'mensaje' => 'El pedido ha sido enviado',
                'usuario_id' => 0,
                'mensajes_automaticos_usuario_id' => $pedidos->vendedor_id,
                'created_at' => now(),
            ];


            $mensaje2 = [
                'chat_id' => $chat_id,
                'mensaje' => 'El pedido ha sido enviado',
                'usuario_id' => 0,
                'mensajes_automaticos_usuario_id' => $pedidos->comprador_id,
                'created_at' => now(),
            ];

            DB::table('mensajes')->insert($mensaje1);
            DB::table('mensajes')->insert($mensaje2);
            Log::channel('desarrollo')->info('La funció envio_realizado  funciona correctament');
            return redirect()->route('ventas.index');
        } catch (Exception $e) {
            Log::error("Error en la función envio_realizado : {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }

    public function envio_recibido(string $id)
    {
        try {
            DB::table('pedidos')
                ->where('id', '=', $id)
                ->update(['status' => 'Envio recibido']);

            $pedidos = DB::table('pedidos')->where('id', $id)->first();

            $chat = DB::table('chats')->where('pedido_id', $id)->first();
            $chat_id = $chat->id;

            Chat::updateChat($id);

            $mensaje1 = [
                'chat_id' => $chat_id,
                'mensaje' => 'El pedido ha sido recibido',
                'usuario_id' => 0,
                'mensajes_automaticos_usuario_id' => $pedidos->vendedor_id,
                'created_at' => now(),
            ];

            $mensaje2 = [
                'chat_id' => $chat_id,
                'mensaje' => 'El pedido ha sido recibido',
                'usuario_id' => 0,
                'mensajes_automaticos_usuario_id' => $pedidos->comprador_id,
                'created_at' => now(),
            ];

            DB::table('mensajes')->insert($mensaje1);
            DB::table('mensajes')->insert($mensaje2);
            Log::channel('desarrollo')->info('La funció envio_recibido  funciona correctament');
            return redirect()->route('ventas.index');
        } catch (Exception $e) {
            Log::error("Error en la función envio_recibido : {$e->getMessage()}");
            return redirect()->route('error.generic');
        }
    }

    public function pedido_rechazado(string $id)
    {
        try {
            DB::table('productos')
                ->join('linea_pedidos', 'linea_pedidos.producto_id', '=', 'productos.id')
                ->where('linea_pedidos.pedido_id', '=', $id)
                ->update(['productos.vendido' => 0]);

            $pedidos = DB::table('pedidos')->where('id', $id)->first();

            DB::table('pedidos')
                ->where('id', '=', $id)
                ->update(['status' => 'Pedido cancelado']);

            $chat = DB::table('chats')->where('pedido_id', $id)->first();
            $chat_id = $chat->id;

            Chat::updateChat($id);

            $mensaje1 = [
                'chat_id' => $chat_id,
                'mensaje' => 'El pedido ha sido cancelado',
                'usuario_id' => 0,
                'mensajes_automaticos_usuario_id' => $pedidos->vendedor_id,
                'created_at' => now(),
            ];

            $mensaje2 = [
                'chat_id' => $chat_id,
                'mensaje' => 'El pedido ha sido cancelado',
                'usuario_id' => 0,
                'mensajes_automaticos_usuario_id' => $pedidos->comprador_id,
                'created_at' => now(),
            ];

            DB::table('mensajes')->insert($mensaje1);
            DB::table('mensajes')->insert($mensaje2);

            return redirect()->route('ventas.index');
        } catch (Exception $e) {
            Log::error("Error en la función pedido_rechazado : {$e->getMessage()}");
            return redirect()->route('error.generic');
        }
    }

    public function pedido_finalizado(string $id)
    {
        try {
            DB::table('pedidos')
                ->where('id', '=', $id)
                ->update(['status' => 'Pedido finalizado']);

            $pedidos = DB::table('pedidos')->where('id', $id)->first();

            $chat = DB::table('chats')->where('pedido_id', $id)->first();
            $chat_id = $chat->id;

            Chat::updateChat($id);

            $mensaje1 = [
                'chat_id' => $chat_id,
                'mensaje' => 'El pedido ha finalizado',
                'usuario_id' => 0,
                'mensajes_automaticos_usuario_id' => $pedidos->comprador_id,
                'created_at' => now(),
            ];

            $mensaje2 = [
                'chat_id' => $chat_id,
                'mensaje' => 'El pedido ha finalizado',
                'usuario_id' => 0,
                'mensajes_automaticos_usuario_id' => $pedidos->vendedor_id,
                'created_at' => now(),
            ];

            DB::table('mensajes')->insert($mensaje1);
            DB::table('mensajes')->insert($mensaje2);
            Log::channel('desarrollo')->info('La funció show de HomeController funciona correctament');
            return redirect()->route('ventas.index');
        } catch (Exception $e) {
            Log::error("Error en la función pedido_finalizado : {$e->getMessage()}");
            return redirect()->route('error.generic');
        }
    }

    public function compras_ordenacion(Request $request)
    {
        try {
            $user = auth()->user()->id;
            $busqueda = $request->all();
            $id_compras_estado = session('id_compras_estado');
            $id_compras_ordenacion = $busqueda['ordenSelect'] ?? '';

            session(["id_compras_ordenacion" => $id_compras_ordenacion]);

            $session_compras_ordenacion = session('id_compras_ordenacion');

            $pedidos = Pedidos::mostrarPedidosCompraFiltrados($id_compras_estado, $session_compras_ordenacion, $user)
                ->paginate(intval(env('PAGINATE_NUM')));

            $pagination = $pedidos->appends([
                'estadoSelect' =>  $id_compras_estado,
                'ordenSelect' => $session_compras_ordenacion
            ]);

            $filtro = [$id_compras_estado,  $session_compras_ordenacion];
            Log::channel('desarrollo')->info('La funció compras_ordenacion  funciona correctament');
            return view('historial.compras', [
                'pedidos' => $pedidos,
                'filtroCompras' => $filtro,
                'pagination' => $pagination
            ]);
        } catch (Exception $e) {
            Log::error("Error en la función compras_ordenacion : {$e->getMessage()}");
            return redirect()->route('error.generic');
        }
    }

    public function compras_estado(Request $request)
    {
        try {
            $user = auth()->user()->id;
            $busqueda = $request->all();
            $id_compras_estado = $busqueda['estadoSelect'] ?? '';
            $id_compras_ordenacion = session('id_compras_ordenacion');

            session(["id_compras_estado" => $id_compras_estado]);

            $session_compras_estado = session('id_compras_estado');

            $pedidos = Pedidos::mostrarPedidosCompraFiltrados($session_compras_estado, $id_compras_ordenacion, $user)
                ->paginate(intval(env('PAGINATE_NUM')));

            $pagination = $pedidos->appends([
                'estadoSelect' =>  $session_compras_estado,
                'ordenSelect' => $id_compras_ordenacion
            ]);

            $filtro = [$session_compras_estado,    $id_compras_ordenacion];
            Log::channel('desarrollo')->info('La funció compras_estado funciona correctament');
            return view('historial.compras', [
                'pedidos' => $pedidos,
                'filtroCompras' => $filtro,
                'pagination' => $pagination
            ]);
        } catch (Exception $e) {
            Log::error("Error en la función compras_estado : {$e->getMessage()}");
            return redirect()->route('error.generic');
        }
    }

    public function ventas_ordenacion(Request $request)
    {
        try {
            $user = auth()->user()->id;
            $busqueda = $request->all();
            $id_ventas_estado = session('id_ventas_estado');
            $id_ventas_ordenacion = $busqueda['ordenSelect'] ?? '';

            session(["id_ventas_ordenacion" => $id_ventas_ordenacion]);

            $session_ventas_ordenacion = session('id_ventas_ordenacion');

            $pedidos = Pedidos::mostrarPedidosVentaFiltrados($id_ventas_estado, $session_ventas_ordenacion, $user)
                ->paginate(intval(env('PAGINATE_NUM')));

            $pagination = $pedidos->appends([
                'estadoSelect' =>  $id_ventas_estado,
                'ordenSelect' => $session_ventas_ordenacion
            ]);

            $filtro = [$id_ventas_estado,  $session_ventas_ordenacion];
            Log::channel('desarrollo')->info('La funció ventas_ordenacio funciona correctament');
            return view('historial.ventas', [
                'pedidos' => $pedidos,
                'filtroVentas' => $filtro,
                'pagination' => $pagination
            ]);
        } catch (Exception $e) {
            Log::error("Error en la función ventas_ordenacion : {$e->getMessage()}");
            return redirect()->route('error.generic');
        }
    }

    public function ventas_estado(Request $request)
    {
        try {
            $user = auth()->user()->id;
            $busqueda = $request->all();
            $id_ventas_estado = $busqueda['estadoSelect'] ?? '';
            $id_ventas_ordenacion = session('id_ventas_ordenacion');

            session(["id_ventas_estado" => $id_ventas_estado]);

            $session_ventas_estado = session('id_ventas_estado');

            $pedidos = Pedidos::mostrarPedidosVentaFiltrados($session_ventas_estado, $id_ventas_ordenacion, $user)
                ->paginate(intval(env('PAGINATE_NUM')));

            $pagination = $pedidos->appends([
                'estadoSelect' =>  $session_ventas_estado,
                'ordenSelect' => $id_ventas_ordenacion
            ]);

            $filtro = [$session_ventas_estado,    $id_ventas_ordenacion];

            Log::channel('desarrollo')->info('La funció ventas_estado funciona correctament');

            return view('historial.ventas', [
                'pedidos' => $pedidos,
                'filtroVentas' => $filtro,
                'pagination' => $pagination
            ]);
        } catch (Exception $e) {
            Log::error("Error en la función ventas_estado : {$e->getMessage()}");
            return redirect()->route('error.generic');
        }
    }

    public function detalle_pedido(string $id)
    {
        try {
            $pedido_comprador = Pedidos::infopedidoscomprador($id)->first();

            $pedido_vendedor = Pedidos::infopedidosvendedor($id)->first();

            if ($pedido_comprador != null ||  $pedido_vendedor != null) {

                if ($pedido_comprador->comprador_id == auth()->user()->id || $pedido_vendedor->vendedor_id == auth()->user()->id) {

                    $linea_pedidos = LineaPedido::mostrarLineaPedidos($id);

                    Log::channel('desarrollo')->info('La funció detalle_pedido funciona correctament');
                    return view('historial.detalle_pedido', compact('linea_pedidos', 'pedido_vendedor', 'pedido_comprador'));
                } else {
                    return redirect()->route('error.generic');
                }
            } else {
                return redirect()->route('error.generic');
            }
        } catch (Exception $e) {
            Log::error("Error en la función detalle_pedido : {$e->getMessage()}");
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