<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // status y reply son campos administrativos; se asignan solo desde métodos del modelo
    protected $fillable = [
        'user_id',
        'name',
        'last_name',
        'email',
        'subject',
        'body',
    ];

    // ========== RELACIONES ==========

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ========== SCOPES ==========

    public function scopeDeRegistrados($query, ?string $estado = null)
    {
        return $query->with('user')
            ->whereNotNull('user_id')
            ->when($estado, fn($q) => $q->where('status', $estado))
            ->latest();
    }

    public function scopeDeInvitados($query, ?string $estado = null)
    {
        return $query->whereNull('user_id')
            ->when($estado, fn($q) => $q->where('status', $estado))
            ->latest();
    }

    // ========== LÓGICA DE NEGOCIO ==========

    public function puedeResolverse(): bool
    {
        return !is_null($this->reply) && $this->reply !== '';
    }

    // Cambia el estado validando que no se resuelva sin respuesta previa.
    public function cambiarEstado(string $nuevoEstado): void
    {
        if ($nuevoEstado === 'Resuelto' && !$this->puedeResolverse()) {
            throw new \Exception('No puedes marcar la consulta como "Resuelta" sin haber enviado una respuesta primero.');
        }
        $this->status = $nuevoEstado;
        $this->save();
    }

    // Guarda la respuesta y cierra la consulta como Resuelta.
    public function responder(string $texto): void
    {
        $this->reply  = $texto;
        $this->status = 'Resuelto';
        $this->save();
    }

    // Crea un mensaje desde el formulario público, soportando usuarios registrados e invitados.
    public static function crearDesdeFrontEnd(?User $user, array $datos): self
    {
        $mensaje = new self();
        $mensaje->user_id   = $user?->id;
        $mensaje->name      = $user ? $user->name      : ($datos['name']      ?? 'Anónimo');
        $mensaje->last_name = $user ? $user->last_name : ($datos['last_name'] ?? '');
        $mensaje->email     = $user ? $user->email     : ($datos['email']     ?? '');
        $mensaje->subject   = $datos['subject'] ?? 'Consulta General';
        $mensaje->body      = $datos['body'];
        $mensaje->status    = 'No leído';
        $mensaje->save();
        return $mensaje;
    }
}
