@extends('layouts.app')

@section('title', $producto->nombre . ' - U-Key')

@section('content')
<div class="container">
    <div class="row">
        <!-- Galería de imágenes del producto -->
        <div class="col-md-5 mb-4">
            <!-- Imagen principal -->
            <div class="producto-detalle-imagen mb-3" style="background-color: #f8f9fa; border-radius: 8px; overflow: hidden;">
                <div style="height: 500px; width: 100%; display: flex; align-items: center; justify-content: center;">
                    <img id="imagenPrincipal" 
                         src="{{ $producto->fotoPrincipal() ? $producto->fotoPrincipal()->url : $producto->imagen_url }}" 
                         class="img-fluid" alt="{{ $producto->nombre }}"
                         style="max-height: 100%; max-width: 100%; width: auto; height: auto; object-fit: contain;">
                </div>
            </div>

            <!-- Miniaturas -->
            @if($producto->fotos->count() > 1)
                <div class="d-flex gap-2 flex-wrap">
                    @foreach($producto->fotos as $foto)
                        <div style="width: 80px; height: 80px; overflow: hidden; border-radius: 8px; background-color: #f8f9fa; border: 2px solid #e9ecef;">
                            <img class="miniatura-foto" 
                                 src="{{ $foto->url }}" 
                                 alt="Miniatura"
                                 style="width: 100%; height: 100%; object-fit: cover; cursor: pointer; opacity: 0.7; transition: opacity 0.3s;"
                                 onclick="cambiarImagen(this)"
                                 onmouseover="this.style.opacity = '1'"
                                 onmouseout="this.style.opacity = '0.7'">
                        </div>
                    @endforeach
                </div>
            @elseif($producto->imagen)
                <!-- Compatibilidad con imagen antigua -->
                <div class="d-flex gap-2 flex-wrap">
                    <div style="width: 80px; height: 80px; overflow: hidden; border-radius: 8px; background-color: #f8f9fa; border: 2px solid #e9ecef;">
                        <img class="miniatura-foto" 
                             src="{{ $producto->imagen_url }}" 
                             alt="Miniatura"
                             style="width: 100%; height: 100%; object-fit: cover; cursor: pointer; opacity: 0.7; transition: opacity 0.3s;"
                             onclick="cambiarImagen(this)"
                             onmouseover="this.style.opacity = '1'"
                             onmouseout="this.style.opacity = '0.7'">
                    </div>
                </div>
            @endif
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
function cambiarImagen(elemento) {
    document.getElementById('imagenPrincipal').src = elemento.src;
    
    // Resetear opacidad de todas las miniaturas
    document.querySelectorAll('.miniatura-foto').forEach(img => {
        img.style.opacity = '0.7';
    });
    
    // Destacar la miniatura seleccionada
    elemento.style.opacity = '1';
}

function agregarCarrito(productoId) {
    if (!{{ auth()->check() ? 'true' : 'false' }}) {
        window.location.href = '{{ route("login") }}';
    } else {
        alert('Función de carrito en desarrollo');
    }
}
</script>
@endsection