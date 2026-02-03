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

        <style>
            .slider-wrapper {
                position: relative;
                padding: 0 40px; /* Espacio para las flechas */
            }
            
            .slider-container {
                overflow: hidden;
                width: 100%;
            }
            
            .slider-track {
                display: flex;
                transition: transform 0.5s ease-in-out;
                width: max-content;
            }
            
            .slider-item {
                width: 300px;
                padding: 0 15px;
                flex-shrink: 0;
            }

            .slider-btn {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                z-index: 10;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: rgba(var(--bs-primary-rgb), 0.1);
                color: var(--bs-primary);
                border: 1px solid var(--bs-primary);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s;
            }

            .slider-btn:hover {
                background: var(--bs-primary);
                color: white;
            }

            .slider-btn.prev { left: 0; }
            .slider-btn.next { right: 0; }
        </style>

        <div class="section-title d-flex justify-content-between align-items-center">
            <h2>Productos Destacados</h2>
        </div>
        
        @if($productosDestacados->count() > 0)
            @if($productosDestacados->count() > 4)
                <!-- Carrusel JS Infinito (> 4 productos) -->
                <div class="slider-wrapper">
                    <button class="slider-btn prev" id="prevBtn"><i class="bi bi-chevron-left"></i></button>
                    
                    <div class="slider-container">
                        <div class="slider-track" id="track">
                            <!-- Productos originales -->
                            @foreach($productosDestacados as $producto)
                                <div class="slider-item">
                                    @include('catalogo.partials.producto_card', ['producto' => $producto])
                                </div>
                            @endforeach
                            
                            <!-- Clones para el efecto infinito (primeros 4 al final) -->
                            @foreach($productosDestacados->take(4) as $producto)
                                <div class="slider-item cloned">
                                    @include('catalogo.partials.producto_card', ['producto' => $producto])
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button class="slider-btn next" id="nextBtn"><i class="bi bi-chevron-right"></i></button>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const track = document.getElementById('track');
                        const nextBtn = document.getElementById('nextBtn');
                        const prevBtn = document.getElementById('prevBtn');
                        
                        const itemWidth = 300; // Ancho del item definido en CSS
                        const totalItems = {{ $productosDestacados->count() }};
                        const clones = 4;
                        let currentIndex = 0;
                        let isTransitioning = false;
                        
                        // Auto play
                        let autoPlay = setInterval(nextSlide, 3000);
                        
                        // Pause on hover
                        const wrapper = document.querySelector('.slider-wrapper');
                        wrapper.addEventListener('mouseenter', () => clearInterval(autoPlay));
                        wrapper.addEventListener('mouseleave', () => autoPlay = setInterval(nextSlide, 3000));

                        function updateTrack() {
                            track.style.transition = 'transform 0.5s ease-in-out';
                            track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
                        }

                        function nextSlide() {
                            if (isTransitioning) return;
                            isTransitioning = true;
                            currentIndex++;
                            updateTrack();
                            
                            // Si llega al final de los clones
                            if (currentIndex >= totalItems) {
                                setTimeout(() => {
                                    track.style.transition = 'none';
                                    currentIndex = 0;
                                    track.style.transform = `translateX(0)`;
                                    isTransitioning = false;
                                }, 500); // Esperar a que termine la transición
                            } else {
                                setTimeout(() => isTransitioning = false, 500);
                            }
                        }

                        function prevSlide() {
                            if (isTransitioning) return;
                            isTransitioning = true;
                            
                            if (currentIndex === 0) {
                                // Saltar al final de los clones instantáneamente
                                track.style.transition = 'none';
                                currentIndex = totalItems;
                                track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
                                
                                // Luego mover al anterior con animación
                                setTimeout(() => {
                                    track.style.transition = 'transform 0.5s ease-in-out';
                                    currentIndex--;
                                    track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
                                    setTimeout(() => isTransitioning = false, 500);
                                }, 10);
                            } else {
                                currentIndex--;
                                updateTrack();
                                setTimeout(() => isTransitioning = false, 500);
                            }
                        }

                        nextBtn.addEventListener('click', nextSlide);
                        prevBtn.addEventListener('click', prevSlide);
                    });
                </script>
            @else
                <!-- Grid estático (<= 4 productos) -->
                <div class="row">
                    @foreach($productosDestacados as $producto)
                        <div class="col-md-6 col-lg-3 mb-4">
                            @include('catalogo.partials.producto_card', ['producto' => $producto])
                        </div>
                    @endforeach
                </div>
            @endif
            
            <!-- Link Ver Todos -->
            <div class="text-center mt-3">
                 <a href="{{ route('catalogo.index') }}" class="btn btn-link text-decoration-none">Ver todos los productos <i class="bi bi-arrow-right"></i></a>
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