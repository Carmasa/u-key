@extends('admin.layouts.admin')

@section('title', 'Gestión de Productos - Admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Gestión de Productos</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.productos.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Nuevo Producto
            </a>
        </div>
    </div>

    <!-- Formulario de Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.productos.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="{{ request('nombre') }}" placeholder="Buscar por nombre...">
                </div>
                
                <div class="col-md-3">
                    <label for="categoria_id" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria_id" name="categoria_id">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(request('categoria_id') == $categoria->id)>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="destacado" class="form-label">Destacado</label>
                    <select class="form-select" id="destacado" name="destacado">
                        <option value="">Todos</option>
                        <option value="1" @selected(request('destacado') === '1')>Destacado</option>
                        <option value="0" @selected(request('destacado') === '0')>No destacado</option>
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Filtrar</button>
                    <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    @if($productos->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Destacado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                        <tr>
                            <td>
                                <strong>{{ $producto->nombre }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $producto->categoria->nombre }}</span>
                            </td>
                            <td>{{ number_format($producto->precio, 2, ',', '.') }}€</td>
                            <td>
                                <span class="badge {{ $producto->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $producto->stock }}
                                </span>
                            </td>
                            <td>
                                @if($producto->destacado)
                                    <span class="badge bg-warning">Sí</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.productos.edit', $producto) }}" 
                                       class="btn btn-warning btn-sm">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.productos.destroy', $producto) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $productos->links() }}
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <strong>No hay productos</strong> - Comienza agregando uno nuevo haciendo clic en el botón arriba.
        </div>
    @endif
</div>
@endsection
