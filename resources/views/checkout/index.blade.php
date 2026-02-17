@extends('layouts.app')

@section('title', 'Checkout - U-Key')

@section('content')
<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('catalogo.index') }}"><i class="bi bi-house me-1"></i>Inicio</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('carrito.index') }}"><i class="bi bi-cart3 me-1"></i>Carrito</a>
                </li>
                <li class="breadcrumb-item active">Checkout</li>
            </ol>
        </nav>
        <h1><i class="bi bi-credit-card me-2"></i>Finalizar Compra</h1>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('checkout.procesar') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Datos de envío -->
            <div class="col-lg-7">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person me-2"></i>Datos de Contacto</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="bi bi-person-fill me-1"></i>Nombre completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre', auth()->user()->nombre ?? '') }}" 
                                       placeholder="Tu nombre completo" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-fill me-1"></i>Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" 
                                       placeholder="tu@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">
                                <i class="bi bi-telephone-fill me-1"></i>Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control @error('telefono') is-invalid @enderror" 
                                   id="telefono" name="telefono" value="{{ old('telefono', auth()->user()->telefono ?? '') }}" 
                                   placeholder="612 345 678" required>
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Dirección de Envío</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="direccion" class="form-label">
                                <i class="bi bi-geo-alt-fill me-1"></i>Dirección completa <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                      id="direccion" name="direccion" rows="3" 
                                      placeholder="Calle, número, piso, código postal, ciudad, provincia" required>{{ old('direccion', auth()->user()->direccion ?? '') }}</textarea>
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información de pago -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Pago Seguro</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" 
                                 alt="Stripe" style="height: 30px;" class="me-3">
                            <span class="text-muted">Pago procesado de forma segura por Stripe</span>
                        </div>
                        <div class="d-flex gap-2">
                            <i class="bi bi-credit-card fs-3 text-muted"></i>
                            <i class="bi bi-credit-card-2-front fs-3 text-muted"></i>
                            <span class="badge bg-success ms-auto"><i class="bi bi-lock me-1"></i>SSL Seguro</span>
                        </div>
                        <p class="small text-muted mt-3 mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Al hacer clic en "Pagar ahora" serás redirigido a la página segura de Stripe para completar el pago.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Resumen del pedido -->
            <div class="col-lg-5">
                <div class="card sticky-top" style="top: 100px;">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Resumen del Pedido</h5>
                    </div>
                    <div class="card-body">
                        <!-- Lista de productos -->
                        <div class="mb-4">
                            @foreach($items as $item)
                                <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <img src="{{ $item->producto->imagen_url }}" 
                                         alt="{{ $item->producto->nombre }}"
                                         class="rounded me-3"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $item->producto->nombre }}</h6>
                                        <small class="text-muted">x{{ $item->cantidad }}</small>
                                    </div>
                                    <span class="fw-bold">
                                        {{ number_format($item->producto->precio * $item->cantidad, 2, ',', '.') }}€
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <!-- Totales -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>{{ number_format($subtotal, 2, ',', '.') }}€</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>
                                <i class="bi bi-truck me-1"></i>Envío
                                @if($envio == 0)
                                    <span class="badge bg-success ms-1">GRATIS</span>
                                @endif
                            </span>
                            <span>
                                @if($envio == 0)
                                    <del class="text-muted me-1">4,99€</del>
                                    <span class="text-success">0,00€</span>
                                @else
                                    {{ number_format($envio, 2, ',', '.') }}€
                                @endif
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 mb-0">Total</span>
                            <span class="h5 mb-0 precio">{{ number_format($total, 2, ',', '.') }}€</span>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-lock me-2"></i>Pagar {{ number_format($total, 2, ',', '.') }}€
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('carrito.index') }}" class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>Volver al carrito
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
