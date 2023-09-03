<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Chat;
use App\Models\Mensaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $mensajes = 0;
            $id_chat = 0;
            $arrayChatsMarcados = [];
            $chats = Chat::mostrarChats(auth()->user()->id)->get();

            foreach ($chats as $key => $chat) {

                $query1 = DB::table('mensajes')
                    ->where('chat_id', $chat->chat_id)
                    ->where('visto', 0)
                    ->where('usuario_id', '!=', auth()->user()->id)
                    ->where('usuario_id', '!=', 0)
                    ->first();

                $query2 = DB::table('mensajes')
                    ->where('chat_id', $chat->chat_id)
                    ->where('visto', 0)
                    ->where('usuario_id', '=', 0)
                    ->where('mensajes_automaticos_usuario_id', '=', auth()->user()->id)
                    ->first();

                if ($query1 || $query2) {
                    $arrayChatsMarcados[] = $chat->chat_id;
                }
            }

            Log::channel('desarrollo')->info('La funció index de ChatController funciona correctament');
            return view('chat.chat', compact('chats', 'mensajes', 'id_chat', 'arrayChatsMarcados'));
        } catch (Exception $e) {
            Log::error("Error en la función index de ChatController: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }

    public function mostrar_chat(string $id)
    {
        try {
            $chat_users =  DB::table('chat_users')->where('chat_id', $id)->where('usuario_id', auth()->user()->id)->first();

            if ($chat_users != null) {

                $chats = Chat::mostrarChats(auth()->user()->id)->get();
                $mensajes = Chat::mostrar_mensajes($id)->get();
                $id_chat = $id;
                Mensaje::updateMensajes($id);

                $arrayChatsMarcados = [];

                foreach ($chats as $key => $chat) {
                    $query1 = DB::table('mensajes')
                        ->where('chat_id', $chat->chat_id)
                        ->where('visto', 0)
                        ->where('usuario_id', '!=', auth()->user()->id)
                        ->where('usuario_id', '!=', 0)
                        ->first();

                    $query2 = DB::table('mensajes')
                        ->where('chat_id', $chat->chat_id)
                        ->where('visto', 0)
                        ->where('usuario_id', '=', 0)
                        ->where('mensajes_automaticos_usuario_id', '=', auth()->user()->id)
                        ->first();

                    if ($query1 || $query2) {
                        $arrayChatsMarcados[] = $chat->chat_id;
                    }
                }
                Log::channel('desarrollo')->info('La funció mostrar_chats de ChatController funciona correctament');
                return view('chat.chat', compact('chats', 'mensajes', 'id_chat', 'arrayChatsMarcados'));
            } else {
                return redirect()->route('error.generic');
            }
        } catch (Exception $e) {
            Log::error("Error en la función mostrar_chats de ChatController: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }

    public function enviar_mensaje(Request $request)
    {
        try {


            if ($request['mensaje'] != null) {

                DB::table('mensajes')->insert([
                    'chat_id' => $request['id'],
                    'mensaje' => $request['mensaje'],
                    'usuario_id' => auth()->user()->id,
                    'created_at' => now(),
                ]);

                DB::table('chats')
                    ->where('pedido_id', '=', $request['id'])
                    ->update(['updated_at' => now()]);
            }
            $id_chat = $request['id'];

            $chats = Chat::mostrarChats(auth()->user()->id)->get();
            $mensajes = Chat::mostrar_mensajes($request['id'])->get();

            $arrayChatsMarcados = [];

            foreach ($chats as $key => $chat) {
                $query1 = DB::table('mensajes')
                    ->where('chat_id', $chat->chat_id)
                    ->where('visto', 0)
                    ->where('usuario_id', '!=', auth()->user()->id)
                    ->where('usuario_id', '!=', 0)
                    ->first();

                $query2 = DB::table('mensajes')
                    ->where('chat_id', $chat->chat_id)
                    ->where('visto', 0)
                    ->where('usuario_id', '=', 0)
                    ->where('mensajes_automaticos_usuario_id', '=', auth()->user()->id)
                    ->first();

                if ($query1 || $query2) {
                    $arrayChatsMarcados[] = $chat->chat_id;
                }
            }


            Log::channel('desarrollo')->info('La funció enviar_mensaje de ChatController funciona correctament');
            return view('chat.chat', compact('chats', 'mensajes', 'id_chat', 'arrayChatsMarcados'));
        } catch (Exception $e) {
            Log::error("Error en la función enviar_mensaje de ChatController: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }

    public function numeroNotificaciones()
    {
        try {
            $chats = Chat::mostrarChats(auth()->user()->id)->get();

            $totalCount = 0;

            foreach ($chats as $chat) {
                if ($chat->usuario_id != auth()->user()->id) {
                    $query1 = DB::table('mensajes')
                        ->where('chat_id', $chat->chat_id)
                        ->where('visto', 0)
                        ->where('usuario_id', '!=', auth()->user()->id)
                        ->where('usuario_id', '!=', 0)
                        ->get();

                    $query2 = DB::table('mensajes')
                        ->where('chat_id', $chat->chat_id)
                        ->where('visto', 0)
                        ->where('usuario_id', '=', 0)
                        ->where('mensajes_automaticos_usuario_id', '=', auth()->user()->id)
                        ->get();

                    $count1 = count($query1);

                    $count2 = count($query2);

                    $totalCount += $count1 +  $count2;
                }
            }
            Log::channel('desarrollo')->info('La funció numeroNotificaciones de ChatController funciona correctament');
            return $totalCount;
        } catch (Exception $e) {
            Log::error("Error en la función numeroNotificaciones de ChatController: {$e->getMessage()}");

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