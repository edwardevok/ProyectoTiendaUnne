<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->filled('estado') ? $request->estado : null;

        $consultasRegistrados = Message::deRegistrados($estado)->get();
        $consultasInvitados   = Message::deInvitados($estado)->get();

        return view('admin.consultas', compact('consultasRegistrados', 'consultasInvitados'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:No leído,En proceso,Resuelto',
        ]);

        $consulta = Message::findOrFail($id);

        try {
            $consulta->cambiarEstado($request->status);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error_estado' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }

    public function storeFrontEnd(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'asunto' => 'nullable|string|max:255',
            'mensaje' => 'required|string|min:1|max:2000',
        ];

        if (!$user) {
            $rules['name']      = 'required|string|max:100';
            $rules['last_name'] = 'nullable|string|max:100';
            $rules['email']     = 'required|email:filter|max:255';
        }

        $validated = $request->validate($rules, [
            'name.required'     => 'El nombre es requerido.',
            'email.required'    => 'El correo es requerido.',
            'email.email'       => 'Ingresa un correo válido.',
            'mensaje.required'  => 'El cuerpo del mensaje no puede estar vacío.',
            'mensaje.max'       => 'El mensaje no puede superar los 2000 caracteres.',
            'asunto.max'        => 'El asunto no puede superar los 255 caracteres.',
        ]);

        Message::crearDesdeFrontEnd($user, [
            'name'      => $validated['name']      ?? null,
            'last_name' => $validated['last_name'] ?? null,
            'email'     => $validated['email']     ?? null,
            'subject'   => $validated['asunto']    ?? 'Consulta General',
            'body'      => $validated['mensaje'],
        ]);

        return redirect()->back()->with('success', 'Tu consulta fue enviada con éxito. ¡Te responderemos pronto!');
    }

    public function sendReply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:2000',
        ]);

        $consulta = Message::findOrFail($id);
        $consulta->responder($request->reply);

        return redirect()->back()->with('success', 'Respuesta enviada y consulta marcada como Resuelta.');
    }
}
