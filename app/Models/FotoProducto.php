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
        'datos_imagen',      // Base64 codificado
        'orden',
        'principal',
    ];

    protected $casts = [
        'principal' => 'boolean',
    ];

    protected $hidden = [
        'datos_imagen',      // Ocultar de serialización JSON por defecto
    ];

    /**
     * 🔒 GLOBAL SCOPE: Nunca cargar datos_imagen automáticamente
     * Evita intentar serializar base64 gigante de forma accidental
     */
    protected static function booted()
    {
        static::addGlobalScope('sin_blob', function (Builder $query) {
            if (!$query->getQuery()->columns) {
                $query->select(
                    'id', 
                    'producto_id', 
                    'nombre_archivo', 
                    'orden', 
                    'principal', 
                    'created_at', 
                    'updated_at'
                );
                // Nota: Excluye 'datos_imagen' automáticamente
            }
        });
    }

    /**
     * Relación: producto propietario
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * 🔓 SCOPE: Cargar INCLUYENDO datos_imagen cuando sea necesario
     * Usar: FotoProducto::withBlobData()->find($id)
     */
    public function scopeWithBlobData($query)
    {
        return $query->withoutGlobalScope('sin_blob');
    }

    /**
     * 🎯 ACCESSOR: Generar URL de la foto
     * Devuelve ruta al endpoint que sirve la imagen
     */
    public function getUrlAttribute()
    {
        // Si existe BLOB, devolver URL al endpoint que lo deserializa
        if ($this->datos_imagen || $this->id) {
            return route('foto.servir', ['foto' => $this->id]);
        }
        
        // Para compatibilidad con archivos locales antiguos
        if ($this->nombre_archivo) {
            return asset('storage/productos/' . $this->nombre_archivo);
        }
        
        return null;
    }
}
