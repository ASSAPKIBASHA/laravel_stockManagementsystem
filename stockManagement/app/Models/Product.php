<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
//here we list the fields we want to give data from the form to db 
//or you can know that they are the fields from the db
    protected $fillable = [
        'product_name',
        'product_code',
        'user_id',//user id is AI
    ];

    public function stockIns()//this is to establish relationship btn products table and stockin
    {
        return $this->hasMany(StockIn::class);//has many means that many stockins
        //  can be done on singl product
    }

    public function stockOuts()//this is also to make relationship with stockouts
    {
        return $this->hasMany(StockOut::class);//hasmany becaousr you can do many stockout on one products
    }

    public function getTotalQuantityAttribute()//this is also for calculating 
    // the totals that are displayed on the dashboard of our system
    {
        return $this->stockIns()->sum('quantity') - $this->stockOuts()->sum('quantity');
    }
    //additionally here we will count total stockins and outs too

    public function user()
    {
        return $this->belongsTo(User::class);//this is diifferent
        //  because all products are owned by a user
    }
}
