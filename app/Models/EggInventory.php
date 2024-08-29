<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggInventory extends Model
{
    use HasFactory;

    protected $table = 'egg_inventory';

    protected $fillable = [
        'egg_category_id',
        'quantity',
        'transaction_type',
        'related_id',
        'user_id',
        'transaction_date',
    ];

    public function eggCategory()
    {
        return $this->belongsTo(EggCategory::class, 'egg_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
