<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mensaje extends Model
{
    use HasFactory;

    public static function updateMensajes($id)
    {
        DB::table('mensajes')->where('chat_id', $id)
            ->where('usuario_id', '!=', auth()->user()->id)
            ->where('usuario_id', '!=', 0)
            ->update(['visto' => 1]); 

        DB::table('mensajes')->where('chat_id', $id)
            ->where('usuario_id', '=', 0)
            ->where('mensajes_automaticos_usuario_id', '=', auth()->user()->id)
            ->update(['visto' => 1]); 

    }
}
