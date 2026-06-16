<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

// role e is_active se asignan solo desde métodos del modelo, no desde input de usuario
#[Fillable(['name', 'last_name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== RELACIONES ==========

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // ========== HELPERS ==========

    public function esAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ========== LÓGICA DE NEGOCIO ==========

    // Crea un usuario nuevo con rol definido. La contraseña la encripta el cast 'hashed'.
    public static function registrar(array $datos, string $rol = 'cliente'): self
    {
        $user = new self();
        $user->name      = $datos['name'];
        $user->last_name = $datos['last_name'];
        $user->email     = $datos['email'];
        $user->password  = $datos['password'];
        $user->role      = $rol;
        $user->is_active = 1;
        $user->save();
        return $user;
    }

    public function actualizarPerfil(string $nombre, string $apellido): void
    {
        $this->name      = $nombre;
        $this->last_name = $apellido;
        $this->save();
    }

    // La contraseña nueva se pasa en texto plano; el cast 'hashed' la encripta.
    public function cambiarContrasena(string $nueva): void
    {
        $this->password = $nueva;
        $this->save();
    }

    public function suspender(): void
    {
        $this->is_active = 0;
        $this->save();
        $this->delete();
    }

    public function restaurar(): void
    {
        $this->restore();
        $this->is_active = 1;
        $this->save();
    }
}
