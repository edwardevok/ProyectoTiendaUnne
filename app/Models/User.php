<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. IMPORTAMOS SOFTDELETES
use App\Models\Order;
use App\Models\Message;

// 👇 ACÁ AGREGAMOS 'is_active' AL FINAL DEL ARRAY
#[Fillable(['name', 'last_name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes; // 2. ACTIVAMOS EL TRAIT AQUÍ

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación con Pedidos
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relación con Consultas
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}