<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $table = 'stock_out';//this is understandable wna
    
    protected $fillable = [
        'product_id',
        'quantity',
    ];

    public function product()//this is also undetstandable
    {
        return $this->belongsTo(Product::class);//the same as from stockin
    }
}
