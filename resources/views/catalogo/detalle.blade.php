@extends('layouts.app')

@section('title', $producto->nombre . ' - U-Key')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('catalogo.index') }}"><i class="bi bi-house-door me-1"></i>Inicio</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('catalogo.categoria', $producto->categoria->slug) }}">
                    {{ $producto->categoria->nombre }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ $producto->nombre }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Galería de imágenes del producto -->
        <div class="col-lg-6 mb-4">
            <!-- Imagen principal -->
            <div class="producto-detalle-imagen mb-3">
                <div style="height: 450px; width: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1a1a25 0%, #12121a 100%);">
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
                        <div class="miniatura-container" style="width: 80px; height: 80px; overflow: hidden; border-radius: 8px; background: var(--bg-elevated); border: 2px solid var(--border-color);">
                            <img class="miniatura-foto" 
                                 src="{{ $foto->url }}" 
                                 alt="Miniatura"
                                 style="width: 100%; height: 100%; object-fit: cover; cursor: pointer; opacity: 0.7; transition: all 0.3s;"
                                 onclick="cambiarImagen(this)"
                                 onmouseover="this.style.opacity = '1'"
                                 onmouseout="this.style.opacity = '0.7'">
                        </div>
                    @endforeach
                </div>
            @elseif($producto->imagen)
                <div class="d-flex gap-2 flex-wrap">
                    <div class="miniatura-container" style="width: 80px; height: 80px; overflow: hidden; border-radius: 8px; background: var(--bg-elevated); border: 2px solid var(--border-color);">
                        <img class="miniatura-foto" 
                             src="{{ $producto->imagen_url }}" 
                             alt="Miniatura"
                             style="width: 100%; height: 100%; object-fit: cover; cursor: pointer; opacity: 1;">
                    </div>
                </div>
            @endif
        </div>

        <!-- Información del producto -->
        <div class="col-lg-6">
            <div class="producto-info">
                @if($producto->destacado)
                    <span class="badge-destacado d-inline-block mb-3" style="position: relative; top: 0; right: 0;">
                        <i class="bi bi-star-fill me-1"></i>DESTACADO
                    </span>
                @endif

                <h1 class="mb-3">{{ $producto->nombre }}</h1>

                <div class="mb-3">
                    <a href="{{ route('catalogo.categoria', $producto->categoria->slug) }}" 
                       class="badge bg-info text-decoration-none">
                        @if($producto->categoria->slug === 'teclados')
                            <i class="bi bi-keyboard me-1"></i>
                        @elseif($producto->categoria->slug === 'ratones')
                            <i class="bi bi-mouse me-1"></i>
                        @else
                            <i class="bi bi-gear me-1"></i>
                        @endif
                        {{ $producto->categoria->nombre }}
                    </a>
                </div>

                <div class="producto-precio mb-4">
                    {{ number_format($producto->precio, 2, ',', '.') }}€
                </div>

                <div class="mb-4">
                    @if($producto->stock > 0)
                        <span class="badge-stock in-stock">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ $producto->stock }} unidades disponibles
                        </span>
                    @else
                        <span class="badge-stock out-of-stock">
                            <i class="bi bi-x-circle-fill"></i>
                            Sin stock
                        </span>
                    @endif
                </div>

                <div class="mb-4">
                    <h5 class="mb-3" style="color: var(--text-secondary);">
                        <i class="bi bi-file-text me-2"></i>Descripción
                    </h5>
                    <p class="text-muted" style="line-height: 1.8;">{{ $producto->descripcion }}</p>
                </div>

                <!-- Características -->
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="p-3 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border-color);">
                            <i class="bi bi-truck text-primary fs-4 d-block mb-2"></i>
                            <small class="text-muted">Envío gratis +50€</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border-color);">
                            <i class="bi bi-shield-check text-primary fs-4 d-block mb-2"></i>
                            <small class="text-muted">Garantía 2 años</small>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mb-4">
                    @if($producto->stock > 0)
                        <form action="{{ route('carrito.agregar') }}" method="POST" class="mb-2">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="bi bi-plus-minus"></i></span>
                                <input type="number" name="cantidad" value="1" min="1" max="{{ $producto->stock }}" 
                                       class="form-control" style="max-width: 100px;">
                                <span class="input-group-text">unidades</span>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="bi bi-cart-plus me-2"></i>Añadir al carrito
                            </button>
                        </form>
                        
                        <a href="{{ route('catalogo.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Seguir comprando
                        </a>
                    @else
                        <button class="btn btn-danger btn-lg" disabled>
                            <i class="bi bi-x-circle me-2"></i>Sin stock disponible
                        </button>
                        <a href="{{ route('catalogo.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Ver otros productos
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Productos relacionados -->
    @if($producto->categoria->productos->count() > 1)
        <section class="mt-5">
            <div class="section-title">
                <span class="icon">🔗</span>
                <h2>Productos Relacionados</h2>
            </div>
            <div class="row">
                @foreach($producto->categoria->productos()->where('id', '!=', $producto->id)->where('visible', true)->limit(4)->get() as $relacionado)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card producto-card h-100">
                            <div class="producto-imagen" style="height: 180px; overflow: hidden;">
                                <img src="{{ $relacionado->imagen_url }}" 
                                     class="card-img-top" alt="{{ $relacionado->nombre }}"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">{{ $relacionado->nombre }}</h6>
                                <p class="precio mt-auto">{{ number_format($relacionado->precio, 2, ',', '.') }}€</p>
                                <a href="{{ route('catalogo.detalle', $relacionado->id) }}" 
                                   class="btn btn-info btn-sm">
                                    <i class="bi bi-eye me-1"></i>Ver detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>

<script>
function cambiarImagen(elemento) {
    document.getElementById('imagenPrincipal').src = elemento.src;
    
    // Resetear opacidad de todas las miniaturas
    document.querySelectorAll('.miniatura-foto').forEach(img => {
        img.style.opacity = '0.7';
        img.parentElement.style.borderColor = 'var(--border-color)';
    });
    
    // Destacar la miniatura seleccionada
    elemento.style.opacity = '1';
    elemento.parentElement.style.borderColor = 'var(--primary)';
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