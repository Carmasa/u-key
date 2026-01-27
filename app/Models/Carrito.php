<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Carrito extends Model
{
    protected $fillable = ['producto_id', 'cantidad', 'session_id'];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
