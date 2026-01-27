@extends('layouts.app')

@section('title', $categoria->nombre . ' - U-Key')

@section('content')
<div class="container">
    <!-- Encabezado de categoría -->
    <div class="mb-5">
        <h1>{{ $categoria->nombre }}</h1>
        <p class="text-muted">{{ $categoria->descripcion }}</p>
    </div>

    <!-- Filtros laterales (opcional para nivel básico) -->
    <div class="row">
        <!-- Sidebar de categorías -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Categorías</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($categorias as $cat)
                        <a href="{{ route('catalogo.categoria', $cat->slug) }}" 
                           class="list-group-item list-group-item-action 
                           {{ $cat->id === $categoria->id ? 'active' : '' }}">
                            {{ $cat->nombre }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Productos de la categoría -->
        <div class="col-md-9">
            @if($productos->count() > 0)
                <div class="row">
                    @foreach($productos as $producto)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card producto-card h-100">
                                <div class="producto-imagen">
                                    <img src="{{ $producto->imagen_url }}" 
                                         class="card-img-top" alt="{{ $producto->nombre }}">
                                    @if($producto->destacado)
                                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">Destacado</span>
                                    @endif
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
                    {{ $productos->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    No hay productos en esta categoría.
                </div>
            @endif
        </div>
    </div>
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