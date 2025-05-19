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
        Schema::create('users', function (Blueprint $table) {
            $table->id();///this is the id of the user
            $table->string('name');//this is his name
            $table->string('email')->unique();//means email is one in whole sysytem
            $table->string('password');//password of the  user
            $table->timestamps();//created at and updated at
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
