@extends('layouts.app')

@section('title', $categoria->nombre . ' - U-Key')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('catalogo.index') }}"><i class="bi bi-house-door me-1"></i>Inicio</a>
            </li>
            <li class="breadcrumb-item active">{{ $categoria->nombre }}</li>
        </ol>
    </nav>

    <!-- Encabezado de categoría -->
    <div class="mb-5">
        <h1>
            @if($categoria->slug === 'teclados')
                <i class="bi bi-keyboard me-2"></i>
            @elseif($categoria->slug === 'ratones')
                <i class="bi bi-mouse me-2"></i>
            @else
                <i class="bi bi-gear me-2"></i>
            @endif
            {{ $categoria->nombre }}
        </h1>
        <p class="text-muted fs-5">{{ $categoria->descripcion }}</p>
    </div>

    <div class="row">
        <!-- Sidebar de categorías -->
        <div class="col-md-3 mb-4">
            <div class="card sidebar-card">
                <div class="card-header">
                    <h5><i class="bi bi-filter me-2"></i>Categorías</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($categorias as $cat)
                        <a href="{{ route('catalogo.categoria', $cat->slug) }}" 
                           class="list-group-item list-group-item-action d-flex align-items-center
                           {{ $cat->id === $categoria->id ? 'active' : '' }}">
                            @if($cat->slug === 'teclados')
                                <i class="bi bi-keyboard me-2"></i>
                            @elseif($cat->slug === 'ratones')
                                <i class="bi bi-mouse me-2"></i>
                            @else
                                <i class="bi bi-gear me-2"></i>
                            @endif
                            {{ $cat->nombre }}
                            <span class="badge bg-secondary ms-auto">{{ $cat->productos->count() }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Productos de la categoría -->
        <div class="col-md-9">
            @if($productos->count() > 0)
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="text-muted mb-0">
                        <i class="bi bi-box me-1"></i>{{ $productos->total() }} productos encontrados
                    </p>
                </div>

                <div class="row">
                    @foreach($productos as $producto)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card producto-card h-100">
                                <div class="producto-imagen" style="height: 220px; overflow: hidden;">
                                    <img src="{{ $producto->imagen_url }}" 
                                         class="card-img-top" alt="{{ $producto->nombre }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                    @if($producto->destacado)
                                        <span class="badge-destacado">
                                            <i class="bi bi-star-fill me-1"></i>HOT
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $producto->nombre }}</h5>
                                    <p class="card-text small">
                                        {{ Str::limit($producto->descripcion, 70) }}
                                    </p>
                                    
                                    @if($producto->stock > 0)
                                        <span class="badge-stock in-stock mb-2" style="width: fit-content;">
                                            <i class="bi bi-check-circle"></i>En stock
                                        </span>
                                    @else
                                        <span class="badge-stock out-of-stock mb-2" style="width: fit-content;">
                                            <i class="bi bi-x-circle"></i>Sin stock
                                        </span>
                                    @endif
                                    
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
                                                    <i class="bi bi-cart-plus me-1"></i>Añadir
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
                    {{ $productos->links() }}
                </div>
            @else
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <h5>No hay productos en esta categoría</h5>
                    <p class="text-muted mb-3">Próximamente añadiremos nuevos productos.</p>
                    <a href="{{ route('catalogo.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-1"></i>Volver al inicio
                    </a>
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