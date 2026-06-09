<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. IMPORTAMOS SOFTDELETES

class Product extends Model
{
    use HasFactory, SoftDeletes; // 2. ACTIVAMOS EL TRAIT AQUÍ

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id', // Cambiamos 'category' por 'category_id'
        'image',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}