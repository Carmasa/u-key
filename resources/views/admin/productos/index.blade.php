@extends('admin.layouts.admin')

@section('title', 'Gestión de Productos - Admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="bi bi-box-seam me-2"></i>Gestión de Productos</h1>
            <p class="text-muted mb-0">Administra el catálogo de productos de la tienda</p>
        </div>
        <a href="{{ route('admin.productos.create') }}" class="btn btn-success btn-lg">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Producto
        </a>
    </div>

    <!-- Formulario de Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Filtros</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.productos.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="nombre" class="form-label">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="{{ request('nombre') }}" placeholder="Buscar por nombre...">
                </div>
                
                <div class="col-md-2">
                    <label for="categoria_id" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria_id" name="categoria_id">
                        <option value="">Todas</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(request('categoria_id') == $categoria->id)>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="stock" class="form-label">Stock</label>
                    <select class="form-select" id="stock" name="stock">
                        <option value="">Todos</option>
                        <option value="con_stock" @selected(request('stock') === 'con_stock')>Con stock</option>
                        <option value="sin_stock" @selected(request('stock') === 'sin_stock')>Sin stock</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="destacado" class="form-label">Destacado</label>
                    <select class="form-select" id="destacado" name="destacado">
                        <option value="">Todos</option>
                        <option value="1" @selected(request('destacado') === '1')>Sí</option>
                        <option value="0" @selected(request('destacado') === '0')>No</option>
                    </select>
                </div>
                
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if($productos->count() > 0)
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="bi bi-tag me-1"></i>Nombre</th>
                            <th><i class="bi bi-folder me-1"></i>Categoría</th>
                            <th><i class="bi bi-currency-euro me-1"></i>Precio</th>
                            <th><i class="bi bi-box me-1"></i>Stock</th>
                            <th><i class="bi bi-eye me-1"></i>Visible</th>
                            <th><i class="bi bi-star me-1"></i>Destacado</th>
                            <th><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td>
                                    <strong>{{ $producto->nombre }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        @if($producto->categoria->slug === 'teclados')
                                            <i class="bi bi-keyboard me-1"></i>
                                        @elseif($producto->categoria->slug === 'ratones')
                                            <i class="bi bi-mouse me-1"></i>
                                        @else
                                            <i class="bi bi-gear me-1"></i>
                                        @endif
                                        {{ $producto->categoria->nombre }}
                                    </span>
                                </td>
                                <td style="font-family: 'Orbitron', sans-serif; color: var(--primary);">
                                    {{ number_format($producto->precio, 2, ',', '.') }}€
                                </td>
                                <td>
                                    @if($producto->stock > 10)
                                        <span class="badge bg-success">{{ $producto->stock }}</span>
                                    @elseif($producto->stock > 0)
                                        <span class="badge bg-warning">{{ $producto->stock }}</span>
                                    @else
                                        <span class="badge bg-danger">0</span>
                                    @endif
                                </td>
                                <td>
                                    @if($producto->visible)
                                        <span class="badge bg-success"><i class="bi bi-check-lg"></i></span>
                                    @else
                                        <span class="badge bg-secondary"><i class="bi bi-x-lg"></i></span>
                                    @endif
                                </td>
                                <td>
                                    @if($producto->destacado)
                                        <span class="badge bg-danger"><i class="bi bi-star-fill"></i></span>
                                    @else
                                        <span class="badge bg-secondary"><i class="bi bi-dash"></i></span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.productos.edit', $producto) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.productos.destroy', $producto) }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $productos->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5" role="alert">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <h5>No hay productos</h5>
            <p class="text-muted mb-3">Comienza agregando uno nuevo haciendo clic en el botón de arriba.</p>
            <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Crear primer producto
            </a>
        </div>
    @endif
</div>
@endsection
