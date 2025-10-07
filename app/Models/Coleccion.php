<?php
// app/Models/Coleccion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coleccion extends Model
{
    // Nombre de la tabla en la BD
    protected $table = 'Coleccion';

    // Clave primaria (coincide con tu migración)
    protected $primaryKey = 'id_coleccion';

    // Desactivamos timestamps para que coincida con tu esquema
    public $timestamps = false;

    // Campos que pueden asignarse masivamente
    protected $fillable = [
        'Nombre'
    ];

    /**
     * Relación muchos a muchos con Producto
     */
    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,
            'Coleccion_Producto', // Tabla pivote
            'id_coleccion',       // Clave de Coleccion en la tabla pivote
            'id_producto'         // Clave de Producto en la tabla pivote
        );
    }
}
