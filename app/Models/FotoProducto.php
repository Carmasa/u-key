<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class FotoProducto extends Model
{
    protected $table = 'fotos_productos';

    protected $fillable = [
        'producto_id',
        'nombre_archivo',
        'datos_imagen',
        'orden',
        'principal',
    ];

    protected $casts = [
        'principal' => 'boolean',
    ];

    protected $hidden = [
        'datos_imagen',
    ];

    /**
     * Aplicar global scope para nunca cargar datos_imagen
     */
    protected static function booted()
    {
        static::addGlobalScope('sin_blob', function (Builder $query) {
            if (!$query->getQuery()->columns) {
                $query->select('id', 'producto_id', 'nombre_archivo', 'orden', 'principal', 'created_at', 'updated_at');
            }
        });
    }

    /**
     * Get the producto that owns the foto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Scope para cargar incluyendo datos_imagen cuando se necesita
     */
    public function scopeWithBlobData($query)
    {
        return $query->withoutGlobalScope('sin_blob');
    }

    /**
     * Get the URL of the foto
     * Si existe datos_imagen (BLOB), devuelve URL al endpoint que la sirve
     * Si no, usa la URL del archivo tradicional (para compatibilidad)
     */
    public function getUrlAttribute()
    {
        // Si tiene BLOB, devolver URL al endpoint que lo sirve
        if ($this->datos_imagen || $this->id) {
            return route('foto.servir', ['foto' => $this->id]);
        }
        
        // Si existe nombre_archivo, usa la URL del archivo tradicional (para compatibilidad)
        if ($this->nombre_archivo) {
            return asset('storage/productos/' . $this->nombre_archivo);
        }
        
        return null;
    }
}
