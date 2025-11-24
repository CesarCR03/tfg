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
        Schema::create('cupones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // El texto: EJ: "REBAJAS20"
            $table->enum('tipo', ['fijo', 'porcentaje']); // Si descuenta 10€ o 10%
            $table->decimal('valor', 8, 2); // La cantidad a descontar
            $table->date('fecha_caducidad')->nullable(); // Opcional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupons');
    }
};
