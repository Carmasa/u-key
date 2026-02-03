<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pedido extends Model
{
    protected $fillable = [
        'numero_pedido',
        'usuario_id',
        'total',
        'subtotal',
        'envio',
        'estado', // Posibles valores: pendiente, nuevo, reparacion, enviado
        'session_id',
        'stripe_session_id',
        'stripe_payment_intent',
        'nombre_cliente',
        'email_cliente',
        'telefono_cliente',
        'direccion_envio',
        'productos'
    ];

    protected $casts = [
        'productos' => 'array',
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'envio' => 'decimal:2',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Generar número de pedido único
     */
    public static function generarNumeroPedido(): string
    {
        do {
            $numero = 'UK-' . strtoupper(uniqid());
        } while (self::where('numero_pedido', $numero)->exists());

        return $numero;
    }

    // Constantes de estado
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_NUEVO = 'nuevo';
    const ESTADO_PREPARACION = 'preparacion'; // Usamos 'preparacion' para "En preparación"
    const ESTADO_ENVIADO = 'enviado';

    /**
     * Scope para pedidos pendientes (incluye nuevos y pendientes por procesar)
     */
    public function scopePendientes($query)
    {
        return $query->whereIn('estado', [self::ESTADO_PENDIENTE, self::ESTADO_NUEVO]);
    }

    /**
     * Scope para pedidos nuevos (no vistos)
     */
    public function scopeNuevos($query)
    {
        return $query->where('estado', self::ESTADO_NUEVO);
    }
}
