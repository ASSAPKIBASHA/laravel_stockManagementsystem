<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_out', function (Blueprint $table) {
            $table->id();//id also of the stockout
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');//this is the fk to allow us to to select the product we are to make stockout 
            $table->integer('quantity');//this is the removal quantity
            $table->timestamps();//created at updated at 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_out');
    }
};
