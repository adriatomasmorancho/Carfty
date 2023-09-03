<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;

    public static function mostrarChats($id){
    
        $query = Chat::join("chat_users", "chats.id", "=", "chat_users.chat_id")
        ->join('users', "users.id", "=", "chat_users.usuario_id")
        ->join('pedidos', "pedidos.id", "=", "chats.pedido_id")
        ->where('pedidos.comprador_id', '=', $id)
        ->orwhere('pedidos.vendedor_id', '=', $id)
        ->where('users.id', '!=', $id)
        ->orderByDesc('chats.updated_at')
        ->select('chats.*', 'users.*', 'chat_users.*', 'chats.created_at as fecha');
        return $query;

    }

    public static function mostrar_mensajes($id){
    
        $query = Chat::join("mensajes", "mensajes.chat_id", "=", "chats.id")
        ->join('pedidos', "pedidos.id", "=", "chats.pedido_id")
        ->where('chats.id', "=", $id)
        ->select('chats.*', 'mensajes.*', 'pedidos.*', 'mensajes.created_at as fecha');
        ;
        return $query;

    }

    public static function updateChat($id){
        DB::table('chats')
        ->where('pedido_id', '=', $id)
        ->update(['updated_at' => now()]);
    }


}
