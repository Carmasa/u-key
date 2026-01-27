@extends('layouts.app')

@section('title', $producto->nombre . ' - U-Key')

@section('content')
<div class="container">
    <div class="row">
        <!-- Imagen del producto -->
        <div class="col-md-5 mb-4">
            <div class="producto-detalle-imagen">
                <img src="{{ $producto->imagen_url }}" 
                     class="img-fluid" alt="{{ $producto->nombre }}">
            </div>
        </div>

        <!-- Información del producto -->
        <div class="col-md-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('catalogo.index') }}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('catalogo.categoria', $producto->categoria->slug) }}">
                            {{ $producto->categoria->nombre }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ $producto->nombre }}</li>
                </ol>
            </nav>

            <h1>{{ $producto->nombre }}</h1>

            @if($producto->destacado)
                <span class="badge bg-danger mb-3">Producto destacado</span>
            @endif

            <div class="mb-3">
                <p class="text-muted">
                    <strong>Categoría:</strong> 
                    <a href="{{ route('catalogo.categoria', $producto->categoria->slug) }}">
                        {{ $producto->categoria->nombre }}
                    </a>
                </p>
            </div>

            <div class="mb-3">
                <h3 class="text-primary">{{ number_format($producto->precio, 2, ',', '.') }}€</h3>
            </div>

            <div class="mb-4">
                <p><strong>Stock disponible:</strong> 
                    @if($producto->stock > 0)
                        <span class="badge bg-success">{{ $producto->stock }} unidades</span>
                    @else
                        <span class="badge bg-danger">Sin stock</span>
                    @endif
                </p>
            </div>

            <div class="mb-4">
                <h5>Descripción</h5>
                <p>{{ $producto->descripcion }}</p>
            </div>

            <div class="d-grid gap-2 mb-4">
                @if($producto->stock > 0)
                    @auth
                        <button class="btn btn-success btn-lg" 
                                onclick="agregarCarrito({{ $producto->id }})">
                            🛒 Añadir al carrito
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                            🛒 Iniciar sesión para comprar
                        </a>
                    @endauth
                    
                    <a href="{{ route('catalogo.index') }}" class="btn btn-outline-secondary">
                        Seguir comprando
                    </a>
                @else
                    <button class="btn btn-danger btn-lg" disabled>
                        ❌ Sin stock disponible
                    </button>
                @endif
            </div>

            <!-- Productos relacionados -->
            @if($producto->categoria->productos->count() > 1)
                <hr>
                <h5>Otros productos de esta categoría</h5>
                <div class="row mt-3">
                    @foreach($producto->categoria->productos()->where('id', '!=', $producto->id)->limit(3)->get() as $relacionado)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <img src="{{ $relacionado->imagen_url }}" 
                                     class="card-img-top" alt="{{ $relacionado->nombre }}">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $relacionado->nombre }}</h6>
                                    <p class="text-primary fw-bold">{{ number_format($relacionado->precio, 2, ',', '.') }}€</p>
                                    <a href="{{ route('catalogo.detalle', $relacionado->id) }}" 
                                       class="btn btn-sm btn-outline-primary">Ver</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
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