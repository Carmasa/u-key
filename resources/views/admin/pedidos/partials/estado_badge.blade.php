@switch($estado)
    @case('pendiente')
        <span class="badge bg-warning text-dark">Pendiente</span>
        @break
    @case('nuevo')
        <span class="badge bg-info text-dark">Nuevo</span>
        @break
    @case('preparacion')
        <span class="badge bg-warning text-dark">En preparación</span>
        @break
    @case('enviado')
        <span class="badge bg-success">Enviado</span>
        @break
    @case('procesado') {{-- Compatibilidad anterior --}}
        <span class="badge bg-secondary">Procesado</span>
        @break
    @default
        <span class="badge bg-secondary">{{ ucfirst($estado) }}</span>
@endswitch
