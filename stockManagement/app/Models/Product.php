<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_code',
        'user_id',
    ];

    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOuts()
    {
        return $this->hasMany(StockOut::class);
    }

    public function getTotalQuantityAttribute()
    {
        return $this->stockIns()->sum('quantity') - $this->stockOuts()->sum('quantity');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
