<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos'; // Opcional si sigues la convención, pero bueno ponerlo

    protected $fillable = [
        'user_id',
        'total',
        'estado'
    ];
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }
}
