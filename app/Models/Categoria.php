<?php
// app/Models/Categoria.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    // Nombre de la tabla en la BD
    protected $table = 'Categoria';

    // Clave primaria
    protected $primaryKey = 'id_categoria';

    // Laravel por defecto espera timestamps; los desactivamos
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
            'Categoria_Producto',
            'id_categoria',
            'id_producto'
        );
    }
}

