<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = 'cupones';

    protected $fillable = ['codigo', 'tipo', 'valor', 'fecha_caducidad'];

    // Helper para saber si está caducado
    public function esValido()
    {
        if ($this->fecha_caducidad && $this->fecha_caducidad < now()) {
            return false;
        }
        return true;
    }
}
