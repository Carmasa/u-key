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
        'estado',
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
}
