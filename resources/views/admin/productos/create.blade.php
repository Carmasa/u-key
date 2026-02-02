@extends('admin.layouts.admin')

@section('title', 'Crear Producto - Admin')

@section('content')
<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.productos.index') }}"><i class="bi bi-box-seam me-1"></i>Productos</a>
                </li>
                <li class="breadcrumb-item active">Crear nuevo</li>
            </ol>
        </nav>
        <h1><i class="bi bi-plus-circle me-2"></i>Crear Nuevo Producto</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Información del producto</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Nombre -->
                        <div class="mb-4">
                            <label for="nombre" class="form-label">
                                <i class="bi bi-tag me-1"></i>Nombre del Producto <span style="color: var(--danger);">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre') }}" 
                                   placeholder="Ej: Teclado Mecánico RGB" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="descripcion" class="form-label">
                                <i class="bi bi-file-text me-1"></i>Descripción <span style="color: var(--danger);">*</span>
                            </label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="4" 
                                      placeholder="Describe las características del producto..." required>{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Precio y Stock -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="precio" class="form-label">
                                        <i class="bi bi-currency-euro me-1"></i>Precio (€) <span style="color: var(--danger);">*</span>
                                    </label>
                                    <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" 
                                           id="precio" name="precio" value="{{ old('precio') }}" 
                                           placeholder="99.99" required>
                                    @error('precio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="stock" class="form-label">
                                        <i class="bi bi-box me-1"></i>Stock <span style="color: var(--danger);">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                           id="stock" name="stock" value="{{ old('stock') }}" 
                                           placeholder="10" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Categoría -->
                        <div class="mb-4">
                            <label for="categoria_id" class="form-label">
                                <i class="bi bi-folder me-1"></i>Categoría <span style="color: var(--danger);">*</span>
                            </label>
                            <select class="form-select @error('categoria_id') is-invalid @enderror" 
                                    id="categoria_id" name="categoria_id" required>
                                <option value="">Selecciona una categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Imágenes -->
                        <div class="mb-4">
                            <label for="fotos" class="form-label">
                                <i class="bi bi-images me-1"></i>Imágenes del Producto
                            </label>
                            <input type="file" class="form-control @error('fotos') is-invalid @enderror" 
                                   id="fotos" name="fotos[]" accept="image/*" multiple>
                            <small class="text-muted">Formatos: JPEG, PNG, JPG, GIF (máx. 2MB). Puedes seleccionar múltiples archivos.</small>
                            @error('fotos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Opciones -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border-color);">
                                    <div class="form-check">
                                        <input type="hidden" name="destacado" value="0">
                                        <input type="checkbox" class="form-check-input" id="destacado" name="destacado" value="1" 
                                               @if(old('destacado')) checked @endif>
                                        <label class="form-check-label" for="destacado">
                                            <i class="bi bi-star-fill me-1" style="color: var(--warning);"></i>
                                            Producto destacado
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border-color);">
                                    <div class="form-check">
                                        <input type="hidden" name="visible" value="0">
                                        <input type="checkbox" class="form-check-input" id="visible" name="visible" value="1" 
                                               @if(old('visible', true)) checked @endif>
                                        <label class="form-check-label" for="visible">
                                            <i class="bi bi-eye-fill me-1" style="color: var(--success);"></i>
                                            Visible en catálogo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-lg me-1"></i>Crear Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
