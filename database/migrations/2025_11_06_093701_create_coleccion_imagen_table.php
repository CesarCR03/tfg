<?php
// ...
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('Coleccion_Imagen')) {
            // Tabla pivote para vincular Colecciones e Imágenes
            Schema::create('Coleccion_Imagen', function (Blueprint $table) {
                $table->unsignedInteger('id_coleccion');
                $table->unsignedBigInteger('id_imagen'); // Asumiendo que Imagen usa bigIncrements

                $table->foreign('id_coleccion')->references('id_coleccion')->on('Coleccion')->onDelete('cascade');
                $table->foreign('id_imagen')->references('id_imagen')->on('Imagen')->onDelete('cascade');

                $table->primary(['id_coleccion', 'id_imagen']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('Coleccion_Imagen');
    }
};
