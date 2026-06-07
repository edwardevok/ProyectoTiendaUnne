<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function products()
    {
        // Esto le dice a Laravel: "Esta categoría es dueña de muchos productos"
        return $this->hasMany(Product::class);
    }
}
