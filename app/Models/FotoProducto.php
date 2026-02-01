<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoProducto extends Model
{
    protected $table = 'fotos_productos';

    protected $fillable = [
        'producto_id',
        'nombre_archivo',
        'orden',
        'principal',
    ];

    protected $casts = [
        'principal' => 'boolean',
    ];

    /**
     * Get the producto that owns the foto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Get the URL of the foto
     */
    public function getUrlAttribute()
    {
        if ($this->nombre_archivo) {
            return asset('storage/productos/' . $this->nombre_archivo);
        }
        return null;
    }
}
