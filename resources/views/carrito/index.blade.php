@extends('layouts.app')

@section('title', 'Carrito de Compra - U-Key')

@section('content')
<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('catalogo.index') }}"><i class="bi bi-house me-1"></i>Inicio</a>
                </li>
                <li class="breadcrumb-item active">Carrito de Compra</li>
            </ol>
        </nav>
        <h1><i class="bi bi-cart3 me-2"></i>Tu Carrito</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($items->count() > 0)
        <div class="row">
            <!-- Lista de productos -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Productos ({{ $totales['num_items'] }})</h5>
                        <form action="{{ route('carrito.vaciar') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                    onclick="return confirm('¿Vaciar todo el carrito?')">
                                <i class="bi bi-trash me-1"></i>Vaciar carrito
                            </button>
                        </form>
                    </div>
                    <div class="card-body p-0">
                        @foreach($items as $item)
                            <div class="carrito-item p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="row align-items-center">
                                    <!-- Imagen -->
                                    <div class="col-md-2 col-3">
                                        <a href="{{ route('catalogo.detalle', $item->producto->id) }}">
                                            <img src="{{ $item->producto->imagen_url }}" 
                                                 alt="{{ $item->producto->nombre }}"
                                                 class="img-fluid rounded"
                                                 style="max-height: 80px; object-fit: cover;">
                                        </a>
                                    </div>
                                    
                                    <!-- Info producto -->
                                    <div class="col-md-4 col-9">
                                        <a href="{{ route('catalogo.detalle', $item->producto->id) }}" 
                                           class="text-decoration-none">
                                            <h6 class="mb-1">{{ $item->producto->nombre }}</h6>
                                        </a>
                                        <small class="text-muted">
                                            <i class="bi bi-folder me-1"></i>{{ $item->producto->categoria->nombre }}
                                        </small>
                                        @if($item->producto->stock < 5)
                                            <br><small class="text-warning">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                Solo quedan {{ $item->producto->stock }} unidades
                                            </small>
                                        @endif
                                    </div>
                                    
                                    <!-- Cantidad -->
                                    <div class="col-md-3 col-6 mt-2 mt-md-0">
                                        <form action="{{ route('carrito.actualizar', $item) }}" method="POST" 
                                              class="d-flex align-items-center">
                                            @csrf
                                            @method('PATCH')
                                            <div class="input-group input-group-sm" style="max-width: 130px;">
                                                <button type="button" class="btn btn-outline-secondary btn-cantidad" 
                                                        data-action="decrease">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" name="cantidad" value="{{ $item->cantidad }}" 
                                                       min="1" max="{{ $item->producto->stock }}"
                                                       class="form-control text-center cantidad-input">
                                                <button type="button" class="btn btn-outline-secondary btn-cantidad" 
                                                        data-action="increase" data-max="{{ $item->producto->stock }}">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- Precio -->
                                    <div class="col-md-2 col-4 mt-2 mt-md-0 text-end">
                                        <span class="precio fw-bold">
                                            {{ number_format($item->producto->precio * $item->cantidad, 2, ',', '.') }}€
                                        </span>
                                        @if($item->cantidad > 1)
                                            <br><small class="text-muted">
                                                {{ number_format($item->producto->precio, 2, ',', '.') }}€/ud
                                            </small>
                                        @endif
                                    </div>
                                    
                                    <!-- Eliminar -->
                                    <div class="col-md-1 col-2 mt-2 mt-md-0 text-end">
                                        <form action="{{ route('carrito.eliminar', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0" 
                                                    title="Eliminar">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('catalogo.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Seguir comprando
                </a>
            </div>

            <!-- Resumen del pedido -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 100px;">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Resumen del Pedido</h5>
                    </div>
                    <div class="card-body">
                        <!-- Barra de progreso envío gratis -->
                        @if(!$totales['envio_gratis'])
                            <div class="envio-gratis-progress mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <small><i class="bi bi-truck me-1"></i>Envío gratuito</small>
                                    <small class="text-primary">
                                        ¡Te faltan {{ number_format($totales['faltan_para_envio_gratis'], 2, ',', '.') }}€!
                                    </small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" 
                                         style="width: {{ min(100, ($totales['subtotal'] / 50) * 100) }}%"></div>
                                </div>
                                <small class="text-muted">Envío gratis en pedidos superiores a 50€</small>
                            </div>
                        @else
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle me-2"></i>
                                <strong>¡Envío gratuito!</strong>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>{{ number_format($totales['subtotal'], 2, ',', '.') }}€</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>
                                <i class="bi bi-truck me-1"></i>Envío
                                @if($totales['envio_gratis'])
                                    <span class="badge bg-success ms-1">GRATIS</span>
                                @endif
                            </span>
                            <span>
                                @if($totales['envio_gratis'])
                                    <del class="text-muted me-1">4,99€</del>
                                    <span class="text-success">0,00€</span>
                                @else
                                    {{ number_format($totales['envio'], 2, ',', '.') }}€
                                @endif
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 mb-0">Total</span>
                            <span class="h5 mb-0 precio">{{ number_format($totales['total'], 2, ',', '.') }}€</span>
                        </div>

                        <div class="d-grid">
                            <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg">
                                <i class="bi bi-credit-card me-2"></i>Proceder al Pago
                            </a>
                            <small class="text-muted text-center mt-2">
                                <i class="bi bi-shield-check me-1"></i>Pago seguro con Stripe
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Carrito vacío -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-cart-x" style="font-size: 5rem; color: var(--text-secondary);"></i>
            </div>
            <h3>Tu carrito está vacío</h3>
            <p class="text-muted mb-4">¡Explora nuestro catálogo y encuentra los mejores periféricos gaming!</p>
            <a href="{{ route('catalogo.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-shop me-2"></i>Ir al Catálogo
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Botones de incrementar/decrementar cantidad
    document.querySelectorAll('.btn-cantidad').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('.cantidad-input');
            const form = this.closest('form');
            let value = parseInt(input.value);
            const max = parseInt(this.dataset.max) || 999;
            
            if (this.dataset.action === 'decrease' && value > 1) {
                input.value = value - 1;
                form.submit();
            } else if (this.dataset.action === 'increase' && value < max) {
                input.value = value + 1;
                form.submit();
            }
        });
    });

    // Submit on enter for quantity input
    document.querySelectorAll('.cantidad-input').forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
</script>
@endpush
@endsection
