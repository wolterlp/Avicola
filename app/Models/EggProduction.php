<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggProduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'quantity',
        'user_id',
        'egg_category_id',
        
    ];

    public function eggCategory()
    {
        return $this->belongsTo(EggCategory::class);
    }
}
