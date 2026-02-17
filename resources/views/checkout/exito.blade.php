@extends('layouts.app')

@section('title', 'Pedido Confirmado - U-Key')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center py-5">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" 
                         style="width: 120px; height: 120px; background: linear-gradient(135deg, var(--success), #00a86b);">
                        <i class="bi bi-check-lg text-white" style="font-size: 4rem;"></i>
                    </div>
                </div>
                
                <h1 class="mb-3">¡Pedido Confirmado!</h1>
                <p class="text-muted lead mb-4">
                    Gracias por tu compra, {{ $pedido->nombre_cliente }}. 
                    Hemos recibido tu pedido correctamente.
                </p>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Detalles del Pedido</h5>
                </div>
                <div class="card-body" style="color: #c0c0d0;">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1" style="color: #e0e0e8;"><strong>Número de Pedido:</strong></p>
                            <p class="h4 precio">{{ $pedido->numero_pedido }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1" style="color: #e0e0e8;"><strong>Fecha:</strong></p>
                            <p style="color: #c0c0d0;">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <hr style="border-color: #3a3a4a;">

                    <h6 class="mb-3" style="color: #e0e0e8;"><i class="bi bi-box-seam me-2"></i>Productos</h6>
                    @foreach($pedido->productos as $producto)
                        <div class="d-flex justify-content-between mb-2" style="color: #c0c0d0;">
                            <span>{{ $producto['nombre'] }} <small style="color: #9090a0;">x{{ $producto['cantidad'] }}</small></span>
                            <span>{{ number_format($producto['precio'] * $producto['cantidad'], 2, ',', '.') }}€</span>
                        </div>
                    @endforeach

                    <hr style="border-color: #3a3a4a;">

                    <div class="d-flex justify-content-between mb-2" style="color: #c0c0d0;">
                        <span>Subtotal</span>
                        <span>{{ number_format($pedido->subtotal, 2, ',', '.') }}€</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="color: #c0c0d0;">
                        <span>Envío</span>
                        <span>
                            @if($pedido->envio == 0)
                                <span class="text-success">Gratis</span>
                            @else
                                {{ number_format($pedido->envio, 2, ',', '.') }}€
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="h5" style="color: #e0e0e8;">Total</span>
                        <span class="h5 precio">{{ number_format($pedido->total, 2, ',', '.') }}€</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-person me-2"></i>Datos de Contacto</h6>
                        </div>
                        <div class="card-body" style="color: #c0c0d0;">
                            <p class="mb-1" style="color: #e0e0e8;"><strong>{{ $pedido->nombre_cliente }}</strong></p>
                            <p class="mb-1">{{ $pedido->email_cliente }}</p>
                            <p class="mb-0">{{ $pedido->telefono_cliente }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-truck me-2"></i>Dirección de Envío</h6>
                        </div>
                        <div class="card-body" style="color: #c0c0d0;">
                            <p class="mb-0">{{ $pedido->direccion_envio }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-envelope me-2"></i>
                Hemos enviado un correo de confirmación a <strong>{{ $pedido->email_cliente }}</strong> 
                con los detalles de tu pedido.
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('catalogo.index') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-shop me-2"></i>Seguir Comprando
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
