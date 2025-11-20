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
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->unsignedInteger('producto_id'); // Tu tabla Producto usa unsignedInteger

            // Guardamos estos datos "congelados" por si cambian en el futuro en la tienda
            $table->string('nombre_producto');
            $table->decimal('precio_unitario', 10, 2);

            $table->integer('cantidad');
            $table->string('talla', 10);
            $table->timestamps();

            // FK manual porque tu tabla se llama 'Producto' (singular/mayúscula)
            $table->foreign('producto_id')->references('id_producto')->on('Producto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_pedidos');
    }
};
