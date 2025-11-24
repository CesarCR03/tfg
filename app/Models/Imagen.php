<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{

    use HasFactory;

    protected $table = 'Imagen';
    protected $primaryKey = 'id_imagen';
    public $timestamps = false;
    protected $fillable = [
        'URL',
        'producto_id',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function colecciones()
    {
        return $this->belongsToMany(
            Coleccion::class,
            'Coleccion_Imagen', // Tabla pivote
            'id_imagen',
            'id_coleccion'
        );
    }
}
