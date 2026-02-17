@extends('admin.layouts.admin')

@section('title', 'Detalle Pedido ' . $pedido->numero_pedido . ' - Admin U-Key')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.pedidos.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
                <i class="bi bi-arrow-left"></i> Volver a pedidos
            </a>
            <h1 class="h3 mb-0">Pedido #{{ $pedido->numero_pedido }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.pedidos.packing-list', $pedido->id) }}" target="_blank" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-text"></i> Lista de Artículos
            </a>
            <a href="{{ route('admin.pedidos.shipping-label', $pedido->id) }}" target="_blank" class="btn btn-outline-dark">
                <i class="bi bi-truck"></i> Etiqueta Envío
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Columna Izquierda: Información del Pedido y Productos -->
        <div class="col-lg-8">
            <!-- Productos -->
            <div class="card shadow-sm mb-4 border-0 bg-transparent text-white">
                <div class="card-header bg-transparent py-3 border-0">
                    <h5 class="card-title mb-0">Artículos del Pedido</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 bg-transparent table-transparent text-white">
                            <thead class="">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio Unit.</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedido->productos as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                {{-- Si tuviéramos imagen, iría aquí --}}
                                                <div>
                                                    <div class="fw-bold">{{ $item['nombre'] ?? 'Producto desconocid' }}</div>
                                                    {{-- <small class="text-muted">SKU: XYZ</small> --}}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item['cantidad'] }}</td>
                                        <td class="text-end">{{ number_format($item['precio'], 2) }}€</td>
                                        <td class="text-end fw-bold">{{ number_format($item['precio'] * $item['cantidad'], 2) }}€</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="">
                                <tr>
                                    <td colspan="3" class="text-end">Subtotal</td>
                                    <td class="text-end">{{ number_format($pedido->subtotal, 2) }}€</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end">Envío</td>
                                    <td class="text-end">{{ number_format($pedido->envio, 2) }}€</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold fs-5">Total</td>
                                    <td class="text-end fw-bold fs-5">{{ number_format($pedido->total, 2) }}€</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Datos de Envío y Cliente -->
            <div class="card shadow-sm mb-4 border-0 bg-transparent text-white">
                <div class="card-header bg-transparent py-3 border-0">
                    <h5 class="card-title mb-0">Información de Envío</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-muted text-uppercase small fw-bold">Cliente</h6>
                            <p class="mb-1 fw-bold">{{ $pedido->nombre_cliente }}</p>
                            <p class="mb-1"><a href="mailto:{{ $pedido->email_cliente }}">{{ $pedido->email_cliente }}</a></p>
                            <p class="mb-0">{{ $pedido->telefono_cliente }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase small fw-bold">Dirección de Entrega</h6>
                            <p class="mb-0" style="white-space: pre-line;">{{ $pedido->direccion_envio }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Acciones y Estado -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4 border-0 bg-transparent text-white">
                <div class="card-header bg-transparent py-3 border-0">
                    <h5 class="card-title mb-0">Estado del Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 text-center">
                        <div class="mb-2">Estado actual:</div>
                        <h4>@include('admin.pedidos.partials.estado_badge', ['estado' => $pedido->estado])</h4>
                    </div>

                    <hr class="bg-light">

                    <form action="{{ route('admin.pedidos.update-status', $pedido->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="d-grid gap-2">
                            <h6 class="fw-bold mb-2">Cambiar estado a:</h6>
                            
                            @if($pedido->estado != 'preparacion')
                                <button type="submit" name="estado" value="preparacion" class="btn btn-warning text-dark">
                                    <i class="bi bi-box-seam me-2"></i>En Preparación
                                </button>
                            @endif

                            @if($pedido->estado != 'enviado')
                                <button type="submit" name="estado" value="enviado" class="btn btn-success">
                                    <i class="bi bi-send me-2"></i>Enviado
                                </button>
                            @endif

                            @if($pedido->estado != 'pendiente' && $pedido->estado != 'nuevo')
                                <button type="submit" name="estado" value="pendiente" class="btn btn-outline-light btn-sm mt-2">
                                    Marcar como Pendiente
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 bg-transparent text-white">
                <div class="card-body">
                    <h6 class="card-title">Información de Pago</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-white-50">ID Sesión:</span>
                        <small class="text-truncate ms-2" style="max-width: 150px;" title="{{ $pedido->stripe_session_id }}">{{ $pedido->stripe_session_id }}</small>
                    </div>
                     <div class="d-flex justify-content-between">
                        <span class="text-white-50">Fecha Pago:</span>
                        <span>{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table-transparent, .table-transparent td, .table-transparent th {
        background-color: transparent !important;
    }
</style>
@endsection
