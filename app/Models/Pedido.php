<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['numero_pedido', 'total', 'estado', 'session_id'];
}
