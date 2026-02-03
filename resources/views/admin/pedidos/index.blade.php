@extends('admin.layouts.admin')

@section('title', 'Gestión de Pedidos - Admin U-Key')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="bi bi-receipt me-2"></i>Gestión de Pedidos</h1>
            <p class="text-muted mb-0">Administra y procesa los pedidos recibidos</p>
        </div>
    </div>

    <!-- Pestañas de navegación -->
    <ul class="nav nav-tabs mb-4" id="pedidosTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $tab == 'ultimos' ? 'active' : '' }}" 
               href="{{ route('admin.pedidos.index', ['tab' => 'ultimos']) }}" 
               role="tab">
               <i class="bi bi-clock-history me-1"></i>Últimos Pedidos
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $tab == 'pendientes' ? 'active' : '' }}" 
               href="{{ route('admin.pedidos.index', ['tab' => 'pendientes']) }}" 
               role="tab">
               <i class="bi bi-exclamation-circle me-1"></i>Pendientes
               @if($countNuevos > 0)
                <span class="badge bg-danger rounded-pill ms-1">{{ $countNuevos }}</span>
               @endif
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $tab == 'todos' ? 'active' : '' }}" 
               href="{{ route('admin.pedidos.index', ['tab' => 'todos']) }}" 
               role="tab">
               <i class="bi bi-list-ul me-1"></i>Todos los Pedidos
            </a>
        </li>
    </ul>

    <div class="tab-content" id="pedidosTabContent">
        
        <!-- Pestaña: Últimos y Pendientes (comparten estructura similar) -->
        @if($tab == 'ultimos' || $tab == 'pendientes')
            <div class="tab-pane fade show active">
                <div class="card">
                    @php
                        $listaPedidos = $tab == 'ultimos' ? $ultimos : $pendientes;
                    @endphp

                    @if($listaPedidos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="bi bi-hash me-1"></i>Pedido</th>
                                        <th><i class="bi bi-person me-1"></i>Cliente</th>
                                        <th><i class="bi bi-calendar me-1"></i>Fecha</th>
                                        <th><i class="bi bi-currency-euro me-1"></i>Total</th>
                                        <th><i class="bi bi-activity me-1"></i>Estado</th>
                                        <th><i class="bi bi-gear me-1"></i>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($listaPedidos as $pedido)
                                        <tr class="{{ $pedido->estado == 'nuevo' ? 'table-new-order' : '' }}">
                                            <td>
                                                <strong>{{ $pedido->numero_pedido }}</strong>
                                            </td>
                                            <td>
                                                <div>{{ $pedido->nombre_cliente }}</div>
                                                <small class="text-muted">{{ $pedido->email_cliente }}</small>
                                            </td>
                                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                            <td style="font-family: 'Orbitron', sans-serif;">{{ number_format($pedido->total, 2) }}€</td>
                                            <td>
                                                @include('admin.pedidos.partials.estado_badge', ['estado' => $pedido->estado])
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-eye me-1"></i>Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            No hay pedidos en esta sección.
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Pestaña: Todos (con buscador) -->
        @if($tab == 'todos')
            <div class="tab-pane fade show active">
                
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('admin.pedidos.index') }}" method="GET" class="d-flex gap-2">
                            <input type="hidden" name="tab" value="todos">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por código, cliente o email..." value="{{ $search }}">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i> Buscar</button>
                            @if($search)
                                <a href="{{ route('admin.pedidos.index', ['tab' => 'todos']) }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="card">
                    @if($todos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="bi bi-hash me-1"></i>Pedido</th>
                                        <th><i class="bi bi-person me-1"></i>Cliente</th>
                                        <th><i class="bi bi-calendar me-1"></i>Fecha</th>
                                        <th><i class="bi bi-currency-euro me-1"></i>Total</th>
                                        <th><i class="bi bi-activity me-1"></i>Estado</th>
                                        <th><i class="bi bi-gear me-1"></i>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todos as $pedido)
                                        <tr class="{{ $pedido->estado == 'nuevo' ? 'table-new-order' : '' }}">
                                            <td><strong>{{ $pedido->numero_pedido }}</strong></td>
                                            <td>
                                                <div>{{ $pedido->nombre_cliente }}</div>
                                                <small class="text-muted">{{ $pedido->email_cliente }}</small>
                                            </td>
                                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                            <td style="font-family: 'Orbitron', sans-serif;">{{ number_format($pedido->total, 2) }}€</td>
                                            <td>
                                                @include('admin.pedidos.partials.estado_badge', ['estado' => $pedido->estado])
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-eye me-1"></i>Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="px-4 py-3 border-top">
                            {{ $todos->appends(['tab' => 'todos', 'search' => $search])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-1 d-block mb-3"></i>
                            No se encontraron pedidos.
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
