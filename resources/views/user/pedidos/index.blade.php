@extends('layouts.app')

@section('title', 'Mis Pedidos - U-Key')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="bi bi-box-seam me-2"></i>Mis Pedidos</h1>

    <div class="card bg-dark text-white border-secondary">
        <div class="card-body p-0">
            @if($pedidos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Pedido #</th>
                                <th>Fecha</th>
                                <th>Artículos</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <!-- <th>Acciones</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedidos as $pedido)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $pedido->numero_pedido }}</span>
                                    </td>
                                    <td>
                                        {{ $pedido->created_at->format('d/m/Y') }}
                                        <small class="d-block text-muted">{{ $pedido->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled mb-0 small">
                                            @foreach($pedido->productos as $producto)
                                                <li>{{ $producto['cantidad'] }}x {{ $producto['nombre'] }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="font-monospace text-success">{{ number_format($pedido->total, 2) }}€</td>
                                    <td>
                                        @php
                                            $badgeClass = match($pedido->estado) {
                                                'pendiente' => 'bg-warning text-dark', // Amarillo
                                                'nuevo' => 'bg-info text-dark', // Azul claro / Cyan
                                                'en_preparacion', 'preparacion' => 'bg-primary', // Azul oscuro
                                                'enviado' => 'bg-success', // Verde
                                                'cancelado' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                            $estadoLabel = match($pedido->estado) {
                                                'pendiente' => 'Pendiente',
                                                'nuevo' => 'Confirmado',
                                                'en_preparacion', 'preparacion' => 'En Preparación',
                                                'enviado' => 'Enviado',
                                                'cancelado' => 'Cancelado',
                                                default => ucfirst($pedido->estado)
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} rounded-pill">
                                            {{ $estadoLabel }}
                                        </span>
                                    </td>
                                    <!-- 
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-light">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td> 
                                    -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                    <h3 class="h5 text-muted">Aún no has realizado ningún pedido.</h3>
                    <a href="{{ route('catalogo.index') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-shop me-2"></i>Ir al Catálogo
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
