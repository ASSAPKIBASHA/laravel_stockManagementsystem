<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;

    protected $table = 'stock_in';//i know that 
    
    protected $fillable = [//fillabkles
        'product_id',
        'quantity',
    ];

    public function product() //here we have only one relationship
    //  becaouse stocin is related to products only 
    {
        return $this->belongsTo(Product::class);//belongs tooo because stockin are performed on the product
    }
}
