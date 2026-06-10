<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        // 1. Consultas de Usuarios Registrados (Tienen user_id)
        $queryRegistrados = Message::whereNotNull('user_id');
        if ($request->has('estado') && $request->estado != '') {
            $queryRegistrados->where('status', $request->estado);
        }
        $consultasRegistrados = $queryRegistrados->latest()->get();

        // 2. Consultas de Visitantes / No Registrados (NO tienen user_id)
        $queryInvitados = Message::whereNull('user_id');
        if ($request->has('estado') && $request->estado != '') {
            $queryInvitados->where('status', $request->estado);
        }
        $consultasInvitados = $queryInvitados->latest()->get();

        // 3. Enviamos ambas listas a la vista
        return view('admin.consultas', compact('consultasRegistrados', 'consultasInvitados'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:No leído,En proceso,Resuelto'
        ]);

        $consulta = Message::findOrFail($id);

        if ($request->status == 'Resuelto' && empty($consulta->reply)) {
            return redirect()->back()->withErrors(['error_estado' => 'No puedes marcar la consulta como "Resuelta" sin haber enviado una respuesta primero.']);
        }

        $consulta->status = $request->status;
        $consulta->save();

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }

    public function storeFrontEnd(Request $request)
    {
        // Capturamos los campos soportando variaciones de nombres del formulario HTML
        $asunto = $request->input('asunto') ?? $request->input('subject') ?? 'Consulta General';
        $mensaje = $request->input('mensaje') ?? $request->input('message') ?? $request->input('body');

        // Si no llega texto en ningún formato, volvemos con un error explícito
        if (empty($mensaje)) {
            return redirect()->back()->withErrors(['mensaje' => 'El cuerpo del mensaje no puede estar vacío.']);
        }

        $user = auth()->user();

        Message::create([
            'user_id'   => $user ? $user->id : null,
            'name'      => $user ? $user->name : ($request->input('name') ?? 'Anónimo'),
            'last_name' => $user ? $user->last_name : $request->input('last_name'),
            'email'     => $user ? $user->email : $request->input('email'),
            'subject'   => $asunto,
            'body'      => $mensaje,
            'status'    => 'No leído',
        ]);

        return redirect()->back()->with('success', 'Tu consulta fue enviada con éxito. ¡Te responderemos pronto!');
    }

    public function sendReply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);

        $consulta = Message::findOrFail($id);
        $consulta->reply = $request->reply;
        $consulta->status = 'Resuelto'; 
        $consulta->save();

        return redirect()->back()->with('success', 'Respuesta enviada y consulta marcada como Resuelta.');
    }
}