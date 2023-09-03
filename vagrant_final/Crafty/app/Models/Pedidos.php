<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedidos extends Model
{
    use HasFactory;

    public static function mostrarPedidosVentaFiltrados($estado, $ordenacion, $id)
    {
        try {
            $query =   DB::table('pedidos')->join("users", "pedidos.comprador_id", "=", "users.id")
            ->select('pedidos.*', 'users.*', 'pedidos.created_at AS fecha','pedidos.id AS pedido_id')
            ->where('pedidos.vendedor_id', '=', $id);

             if (!empty($estado)) {
                switch ($estado) {
                    case 1:
                        $query->where ('pedidos.status', '=', 'Pendiente');
                        break;
                    case 2:
                        $query->where ('pedidos.status', '=', 'Pago aceptado');
                        break;
                    case 3:
                        $query->where ('pedidos.status', '=', 'Envio hecho');;
                        break;
                    case 4:
                        $query->where ('pedidos.status', '=', 'Envio recibido');;
                        break;
                    case 5:
                        $query->where ('pedidos.status', '=', 'Pedido Finalizado');;
                        break;
                }
            }
           
            if (!empty($ordenacion)) {
                switch ($ordenacion) {
                    case 1:
                        $query->orderByDesc('pedidos.created_at');
                        break;
                    case 2:
                        $query->orderBy('pedidos.created_at');
                        break;
                    case 3:
                        $query->orderBy('pedidos.precio_pedido');
                        break;
                    case 4:
                        $query->orderByDesc('pedidos.precio_pedido');
                        break;
                }
            }


            $query->orderBy('pedidos.created_at');


            return $query;


        } catch (Exception $e) {
            Log::channel('desarrollo')->info('Exception error' . $e->getMessage());
        }
    }

    

    public static function mostrarPedidosCompraFiltrados($estado, $ordenacion, $id)
    {
        try {


            $query =   DB::table('pedidos')->join("users", "pedidos.vendedor_id", "=", "users.id")
            ->select('pedidos.*', 'users.*', 'pedidos.created_at AS fecha','pedidos.id AS pedido_id')
            ->where('pedidos.comprador_id', '=', $id);

            if (!empty($estado)) {
                switch ($estado) {
                    case 1:
                        $query->where ('pedidos.status', '=', 'Pendiente');
                        break;
                    case 2:
                        $query->where ('pedidos.status', '=', 'Pago aceptado');
                        break;
                    case 3:
                        $query->where ('pedidos.status', '=', 'Envio hecho');;
                        break;
                    case 4:
                        $query->where ('pedidos.status', '=', 'Envio recibido');;
                        break;
                    case 5:
                        $query->where ('pedidos.status', '=', 'Pedido Finalizado');;
                        break;
                }
            }
           
            if (!empty($ordenacion)) {
                switch ($ordenacion) {
                    case 1:
                        $query->orderByDesc('pedidos.created_at');
                        break;
                    case 2:
                        $query->orderBy('pedidos.created_at');
                        break;
                    case 3:
                        $query->orderBy('pedidos.precio_pedido');
                        break;
                    case 4:
                        $query->orderByDesc('pedidos.precio_pedido');
                        break;
                }
            }


            $query->orderBy('pedidos.created_at');


            return $query;


        } catch (Exception $e) {
            Log::channel('desarrollo')->info('Exception error' . $e->getMessage());
        }
    }

    public static function infopedidoscomprador($id)
    {
        $query = Pedidos::join("users", "pedidos.comprador_id", "=", "users.id")
            ->where('pedidos.id', '=', $id)
            ->select('pedidos.*', 'users.*', 'pedidos.created_at AS fecha');
        return $query;
    }

    public static function infopedidosvendedor($id)
    {
        $query = Pedidos::join("users", "pedidos.vendedor_id", "=", "users.id")
        ->where('pedidos.id', '=', $id)
        ->select('pedidos.*', 'users.*', 'pedidos.created_at AS fecha');
        return $query;
    }

    public static function pedidosVendedor($user)
    {
        $query = DB::table('pedidos')->join("users", "pedidos.comprador_id", "=", "users.id")
        ->where('pedidos.vendedor_id', '=', $user)
        ->select('pedidos.*', 'users.*', 'pedidos.created_at AS fecha', 'pedidos.id AS pedido_id');

        return $query;
    }

    public static function pedidosComprador($user)
    {
        $query = DB::table('pedidos')->join("users", "pedidos.vendedor_id", "=", "users.id")
        ->where('pedidos.comprador_id', '=', $user)
        ->select('pedidos.*', 'users.*', 'pedidos.created_at AS fecha', 'pedidos.id AS pedido_id');
        return $query;
    }



}