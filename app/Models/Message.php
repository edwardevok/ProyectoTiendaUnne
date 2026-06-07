<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'last_name',
        'email',
        'subject', // <-- Añadir aquí
        'body',
        'status',
        'reply',
    ];

    // Le decimos a Laravel que este mensaje le pertenece a un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
