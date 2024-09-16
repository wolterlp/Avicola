<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'egg_category_id', 
        'user_id', 
        'quantity', 
        'price_per_unit', 
        'total_price'
    ];

    public function eggCategory()
    {
        return $this->belongsTo(EggCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eggInventory()
    {
        return $this->belongsTo(EggInventory::class, 'egg_category_id', 'egg_category_id');
    }
}
