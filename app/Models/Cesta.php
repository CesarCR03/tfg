<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cesta extends Model
{
    // Mapeo a la tabla Cesta
    protected $table = 'Cesta';

    // Clave primaria
    protected $primaryKey = 'id_cesta';

    // Desactivamos timestamps si la tabla no tiene created_at/updated_at
    // (Asegúrate de que tu tabla los tenga, como en el SQL proporcionado)
    // public $timestamps = false;

    // Campos que pueden asignarse masivamente
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    /**
     * Relación: Una cesta pertenece a un Usuario (tabla 'User').
     */
    public function user(): BelongsTo
    {
        // Importante: Referencia correcta a la tabla 'User'
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación: Una cesta tiene muchos productos (tabla pivote 'Cesta_Producto').
     */
    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(
            Producto::class,
            'Cesta_Producto', // Nombre de la tabla pivote
            'cesta_id',       // Clave de la cesta en la tabla pivote
            'id_producto'     // Clave del producto en la tabla pivote
        )->withPivot('cantidad', 'talla'); // Incluye los datos adicionales de la tabla pivote
    }
}
