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
        Schema::create('producto_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_producto'); // Clave foránea
            $table->string('talla', 10);
            $table->integer('stock');
            $table->timestamps(); // Opcional, pero recomendado

            // Definir la clave foránea
            $table->foreign('id_producto')
                ->references('id_producto')->on('Producto')
                ->onDelete('cascade'); // Si se borra el producto, se borra el stock

            // Evitar duplicados (que un producto no tenga dos "Talla S")
            $table->unique(['id_producto', 'talla']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_stock');
    }
};
