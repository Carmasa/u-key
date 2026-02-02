<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'precio',
        'stock',
        'imagen',
        'categoria_id',
        'destacado',
        'visible',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'destacado' => 'boolean',
        'visible' => 'boolean',
    ];

    /**
     * Get the categoria that owns the producto.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Get all carritos for the producto.
     */
    public function carritos(): HasMany
    {
        return $this->hasMany(Carrito::class);
    }

    /**
     * Get all fotos for the producto.
     */
    public function fotos(): HasMany
    {
        return $this->hasMany(FotoProducto::class)->orderBy('orden');
    }

    /**
     * Get the principal foto or the first one
     */
    public function fotoPrincipal()
    {
        // Si ya están cargadas las fotos, usar la colección
        if ($this->relationLoaded('fotos')) {
            $principal = $this->fotos->where('principal', true)->first();
            return $principal ?? $this->fotos->first();
        }
        
        // Si no, hacer la query
        return $this->fotos()->where('principal', true)->first() ?? $this->fotos()->first();
    }

    /**
     * Get the full image URL - prioriza fotos nuevas, luego imagen antigua
     */
    public function getImagenUrlAttribute()
    {
        // Primero intenta con fotos nuevas
        $fotoPrincipal = $this->fotoPrincipal();
        if ($fotoPrincipal) {
            return $fotoPrincipal->url;
        }
        
        // Si no hay fotos nuevas, usa la imagen antigua
        if ($this->imagen) {
            return asset('storage/productos/' . $this->imagen);
        }
        
        // Si no hay nada, retorna imagen placeholder
        return 'https://media.istockphoto.com/id/1128826884/es/vector/ning%C3%BAn-s%C3%ADmbolo-de-vector-de-imagen-falta-icono-disponible-no-hay-galer%C3%ADa-para-este-momento.jpg?s=612x612&w=0&k=20&c=9vnjI4XI3XQC0VHfuDePO7vNJE7WDM8uzQmZJ1SnQgk=';
    }
}
