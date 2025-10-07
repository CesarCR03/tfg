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
        Schema::create('Categoria_Producto', function (Blueprint $table) {
            $table->unsignedInteger('id_categoria');
            $table->unsignedInteger('id_producto');
            $table->foreign('id_categoria')
                ->references('id_categoria')->on('Categoria');
            $table->foreign('id_producto')
                ->references('id_producto')->on('Producto');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Categoria_Producto');
    }
};
