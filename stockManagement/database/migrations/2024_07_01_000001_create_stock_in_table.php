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
        Schema::create('stock_in', function (Blueprint $table) {
            $table->id();//this is the id of the stockin
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');//this 
            // is the foreign key from the products table that have to be used while we are
            // making increase of the quantinty of the produdct 
            $table->integer('quantity');//this is the quantity that is added by the user 
            $table->timestamps();//this is created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_in');
    }
};
