@extends('layouts.app')

@section('title', 'U-Key - Teclados y Periféricos Gaming')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <section class="hero-section mb-5">
        <h1><i class="bi bi-lightning-charge-fill me-2"></i>EQUIPA TU SETUP</h1>
        <p>Descubre nuestra colección de teclados mecánicos, ratones gaming y accesorios premium para llevar tu experiencia al siguiente nivel.</p>
        <div class="mt-4">
            <a href="{{ route('catalogo.categoria', ['slug' => 'teclados']) }}" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-keyboard me-2"></i>Ver Teclados
            </a>
            <a href="{{ route('catalogo.categoria', ['slug' => 'ratones']) }}" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-mouse me-2"></i>Ver Ratones
            </a>
        </div>
    </section>

    <!-- Productos Destacados -->
    <section class="mb-5">
        <div class="section-title">
            <h2>Productos Destacados</h2>
        </div>
        
        @if($productosDestacados->count() > 0)
            <div class="row">
                @foreach($productosDestacados as $producto)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card producto-card h-100">
                            <div class="producto-imagen" style="height: 250px; overflow: hidden;">
                                <img src="{{ $producto->imagen_url }}" 
                                     class="card-img-top" alt="{{ $producto->nombre }}"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                                @if($producto->destacado)
                                    <span class="badge-destacado">
                                        <i class="bi bi-star-fill me-1"></i>DESTACADO
                                    </span>
                                @endif
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <span class="badge bg-info mb-2" style="width: fit-content;">
                                    {{ $producto->categoria->nombre }}
                                </span>
                                <h5 class="card-title">{{ $producto->nombre }}</h5>
                                <p class="card-text small">
                                    {{ Str::limit($producto->descripcion, 70) }}
                                </p>
                                <p class="precio mt-auto">
                                    {{ number_format($producto->precio, 2, ',', '.') }}€
                                </p>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('catalogo.detalle', $producto->id) }}" 
                                       class="btn btn-info">
                                        <i class="bi bi-eye me-1"></i>Ver detalles
                                    </a>
                                    
                                    @if($producto->stock > 0)
                                        <form action="{{ route('carrito.agregar') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                            <input type="hidden" name="cantidad" value="1">
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="bi bi-cart-plus me-1"></i>Añadir al carrito
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary" disabled>
                                            <i class="bi bi-x-circle me-1"></i>Sin stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $productosDestacados->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>No hay productos destacados en este momento.
            </div>
        @endif
    </section>

    <!-- Categorías -->
    <section>
        <div class="section-title">
            <span class="icon">📦</span>
            <h2>Explora por Categoría</h2>
        </div>
        
        <div class="row">
            @forelse($categorias as $categoria)
                <div class="col-md-4 mb-4">
                    <div class="card categoria-card h-100">
                        <div class="card-body text-center py-5">
                            <span class="categoria-icon">
                                @if($categoria->slug === 'teclados')
                                    ⌨️
                                @elseif($categoria->slug === 'ratones')
                                    🖱️
                                @else
                                    🎮
                                @endif
                            </span>
                            <h5 class="card-title mt-3">{{ $categoria->nombre }}</h5>
                            <p class="card-text">{{ $categoria->descripcion }}</p>
                            <a href="{{ route('catalogo.categoria', $categoria->slug) }}" 
                               class="btn btn-outline-primary mt-3">
                                <i class="bi bi-arrow-right me-1"></i>Explorar
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>No hay categorías disponibles.
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Features Section -->
    <section class="mt-5">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="p-4">
                    <i class="bi bi-truck fs-1 text-primary mb-3 d-block"></i>
                    <h5>Envío Rápido</h5>
                    <p class="text-muted small">Entrega en 24-48h en península</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4">
                    <i class="bi bi-shield-check fs-1 text-primary mb-3 d-block"></i>
                    <h5>Garantía 2 Años</h5>
                    <p class="text-muted small">Todos nuestros productos están garantizados</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4">
                    <i class="bi bi-headset fs-1 text-primary mb-3 d-block"></i>
                    <h5>Soporte 24/7</h5>
                    <p class="text-muted small">Estamos aquí para ayudarte siempre</p>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function agregarCarrito(productoId) {
    if (!{{ auth()->check() ? 'true' : 'false' }}) {
        window.location.href = '{{ route("login") }}';
    } else {
        alert('Función de carrito en desarrollo');
    }
}
</script>
@endsection