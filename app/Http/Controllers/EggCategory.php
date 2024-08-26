<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EggCategory extends Controller
{
    use HasFactory;

    protected $fillable = [
        'categoria',
        'descripcion',
        'total_egg',
    ];

    /**
     * Relación con el modelo EggProduction.
     */
    public function eggProductions()
    {
        return $this->hasMany(EggProduction::class, 'egg_category_id');
    }
}
