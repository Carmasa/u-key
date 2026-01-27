@extends('layouts.app')

@section('title', 'Catálogo - U-Key')

@section('content')
<div class="container">
    <!-- Productos Destacados -->
    <section class="mb-5">
        <h2 class="mb-4">Productos Destacados</h2>
        
        @if($productosDestacados->count() > 0)
            <div class="row">
                @foreach($productosDestacados as $producto)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card producto-card h-100">
                            <div class="producto-imagen">
                                <img src="{{ $producto->imagen_url }}" 
                                     class="card-img-top" alt="{{ $producto->nombre }}">
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">Destacado</span>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $producto->nombre }}</h5>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($producto->descripcion, 80) }}
                                </p>
                                <p class="text-primary fw-bold fs-5 mt-auto">
                                    {{ number_format($producto->precio, 2, ',', '.') }}€
                                </p>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('catalogo.detalle', $producto->id) }}" 
                                       class="btn btn-info btn-sm">Ver detalles</a>
                                    
                                    @auth
                                        <button class="btn btn-success btn-sm" 
                                                onclick="agregarCarrito({{ $producto->id }})">
                                            🛒 Añadir al carrito
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-success btn-sm">
                                            🛒 Añadir al carrito
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $productosDestacados->links() }}
            </div>
        @else
            <div class="alert alert-info">
                No hay productos destacados en este momento.
            </div>
        @endif
    </section>

    <!-- Todas las categorías -->
    <section>
        <h2 class="mb-4">Explora nuestras categorías</h2>
        <div class="row">
            @forelse($categorias as $categoria)
                <div class="col-md-4 mb-3">
                    <div class="card categoria-card text-center">
                        <div class="card-body">
                            <h5 class="card-title">{{ $categoria->nombre }}</h5>
                            <p class="card-text text-muted">{{ $categoria->descripcion }}</p>
                            <a href="{{ route('catalogo.categoria', $categoria->slug) }}" 
                               class="btn btn-outline-primary">
                                Ver categoría
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    No hay categorías disponibles.
                </div>
            @endforelse
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