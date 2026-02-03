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
