<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'U-Key - Teclados y Periféricos Gaming')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('catalogo.index') }}">
                <i class="bi bi-keyboard me-2"></i>U-KEY
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('catalogo.index') ? 'active' : '' }}" href="{{ route('catalogo.index') }}">
                            <i class="bi bi-house-door me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('categoria/teclados*') ? 'active' : '' }}" href="{{ route('catalogo.categoria', ['slug' => 'teclados']) }}">
                            <i class="bi bi-keyboard me-1"></i>Teclados
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('categoria/ratones*') ? 'active' : '' }}" href="{{ route('catalogo.categoria', ['slug' => 'ratones']) }}">
                            <i class="bi bi-mouse me-1"></i>Ratones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('categoria/accesorios*') ? 'active' : '' }}" href="{{ route('catalogo.categoria', ['slug' => 'accesorios']) }}">
                            <i class="bi bi-gear me-1"></i>Accesorios
                        </a>
                    </li>
                </ul>
                
                <!-- Carrito -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('carrito.index') }}">
                            <i class="bi bi-cart3 me-1"></i>Carrito
                            <span class="carrito-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" 
                                  id="carrito-contador" style="display: none;">
                                0
                            </span>
                        </a>
                    </li>
                </ul>

                <!-- Auth Links -->
                <ul class="navbar-nav ms-auto">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.productos.index') }}">
                                    <i class="bi bi-gear-fill me-1"></i>Panel Admin
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->nombre }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Registrarse
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Messages -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3" role="alert">
            @foreach ($errors->all() as $error)
                <div><i class="bi bi-exclamation-triangle me-2"></i>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center py-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="text-gradient mb-3">U-KEY</h5>
                    <p class="text-muted small">Tu tienda especializada en teclados mecánicos, ratones gaming y accesorios de alta calidad.</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="mb-3" style="color: var(--text-secondary);">Enlaces</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('catalogo.index') }}" class="text-muted small">Inicio</a></li>
                        <li><a href="{{ route('catalogo.categoria', 'teclados') }}" class="text-muted small">Teclados</a></li>
                        <li><a href="{{ route('catalogo.categoria', 'ratones') }}" class="text-muted small">Ratones</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-3" style="color: var(--text-secondary);">Síguenos</h6>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="text-muted fs-4"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-muted fs-4"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-muted fs-4"><i class="bi bi-discord"></i></a>
                        <a href="#" class="text-muted fs-4"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr style="border-color: var(--border-color);">
            <p class="mb-0">&copy; 2026 U-Key. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Actualizar contador del carrito -->
    <script>
        function actualizarContadorCarrito() {
            fetch('{{ route('carrito.contador') }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('carrito-contador');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }
                });
        }
        document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);
    </script>
    
    @stack('scripts')
</body>
</html>