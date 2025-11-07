<?php
// app/Models/Producto.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    // Nombre de la tabla en la BD
    protected $table = 'Producto';

    // Clave primaria
    protected $primaryKey = 'id_producto';

    // Desactivamos timestamps si la tabla no tiene created_at/updated_at
    public $timestamps = false;

    // Campos que pueden asignarse masivamente
    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Precio',   // en euros (€), número decimal
    ];

    /**
     * Relación muchos a muchos con Categoria
     */
    public function categorias()
    {
        return $this->belongsToMany(
            Categoria::class,
            'Categoria_Producto',
            'id_producto',
            'id_categoria'
        );
    }

    // app/Models/Producto.php (dentro de la clase Producto)
// ...


// Relación muchos a muchos con Coleccion
    public function colecciones()
    {
        return $this->belongsToMany(
            Coleccion::class,
            'Coleccion_Producto',
            'id_producto',
            'id_coleccion'
        );
    }
    /**
     * Relación uno a muchos con Imagen
     */
    public function imagenes()
    {
        return $this->hasMany(Imagen::class, 'producto_id');
    }

    public function tallas()
    {
        // Un producto tiene muchas tallas/stocks
        return $this->hasMany(ProductoStock::class, 'id_producto');
    }
}

