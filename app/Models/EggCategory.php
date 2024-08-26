<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggCategory extends Model
{
    protected $fillable = ['category', 'description'];

    public function eggProductions()
    {
        return $this->hasMany(EggProduction::class);
    }
}
