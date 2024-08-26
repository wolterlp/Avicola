<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventario extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 
        'quantity', 
        'user_id', 
        'egg_category_id',
    ];

    // Relación con EggCategory
    public function eggCategory()
    {
        return $this->belongsTo(EggCategory::class, 'egg_category_id');
    }
}
