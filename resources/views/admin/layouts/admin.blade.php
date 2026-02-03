<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - U-Key')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .table-new-order {
            background-color: #1e4f66 !important; /* Darker soft cyan */
            font-weight: bold;
            color: #ffffff;
        }
        .table-new-order .text-muted {
            color: #e0e0e0 !important; /* Light gray for readability on dark background */
        }
        .table-transparent, .table-transparent td, .table-transparent th {
            background-color: transparent !important;
            color: inherit; /* Ensure text color adapts */
        }
    </style>
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.productos.index') }}">
                <i class="bi bi-gear-fill me-2"></i>U-KEY ADMIN
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}" 
                           href="{{ route('admin.productos.index') }}">
                            <i class="bi bi-box-seam me-1"></i>Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}" 
                           href="{{ route('admin.pedidos.index') }}">
                            <i class="bi bi-receipt me-1"></i>Pedidos
                            @php
                                $nuevos = \App\Models\Pedido::nuevos()->count();
                            @endphp
                            @if($nuevos > 0)
                                <span class="badge bg-danger rounded-pill ms-1" style="font-size: 0.7em;">{{ $nuevos }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
                
                <!-- Auth Links -->
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('catalogo.index') }}">
                                <i class="bi bi-arrow-left me-1"></i>Volver a la tienda
                            </a>
                        </li>
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

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3" role="alert">
            <strong><i class="bi bi-exclamation-triangle me-2"></i>Errores de validación:</strong>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main class="py-4 flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center py-4">
        <p class="mb-0">&copy; 2026 U-Key - Panel de Administración</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
