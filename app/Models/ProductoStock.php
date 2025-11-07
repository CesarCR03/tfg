<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoStock extends Model
{
    protected $table = 'Producto_stock';
    protected $fillable = ['id_producto', 'talla', 'stock'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
