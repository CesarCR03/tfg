<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('Producto', function (Blueprint $table) {
            $table->increments('id_producto');
            $table->string('Nombre');
            $table->text('Descripcion');
            $table->decimal('Precio', 8, 2);
            $table->integer('Stock');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Producto');
    }
};
