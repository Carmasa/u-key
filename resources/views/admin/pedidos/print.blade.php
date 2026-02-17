<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento Pedido {{ $pedido->numero_pedido }}</title>
    <style>
        body { font-family: sans-serif; line-height: 1.5; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .doc-title { font-size: 18px; text-transform: uppercase; color: #666; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .section-title { font-weight: bold; border-bottom: 1px solid #ddd; margin-bottom: 10px; padding-bottom: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        .table th { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; padding-top: 20px; }
        
        @media print {
            body { font-size: 12pt; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align:right;">
        <button onclick="window.print()">Imprimir</button>
        <button onclick="window.close()">Cerrar</button>
    </div>

    <div class="header">
        <div class="logo">U-KEY</div>
        <div class="doc-title">
            @if($type == 'packing_list')
                Lista de Artículos (Packing List)
            @else
                Datos de Envío
            @endif
        </div>
        <div>Pedido #{{ $pedido->numero_pedido }}</div>
    </div>

    <div class="info-grid">
        <div>
            <div class="section-title">Remitente</div>
            <strong>U-Key Inc.</strong><br>
            Calle Ejemplo 123<br>
            28000 Madrid, España<br>
            admin@u-key.com
        </div>
        <div>
            <div class="section-title">Destinatario</div>
            <strong>{{ $pedido->nombre_cliente }}</strong><br>
            <div style="white-space: pre-line;">{{ $pedido->direccion_envio }}</div>
            <br>
            Tel: {{ $pedido->telefono_cliente }}<br>
            Email: {{ $pedido->email_cliente }}
        </div>
    </div>

    @if($type == 'packing_list')
        <div class="section-title">Detalle del Contenido</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-right">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido->productos as $item)
                    <tr>
                        <td>{{ $item['nombre'] ?? 'Item' }}</td>
                        <td class="text-right">{{ $item['cantidad'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="margin-top: 40px; border: 1px solid #ccc; padding: 20px;">
            <strong>Notas de preparación:</strong>
            <br><br><br>
        </div>
    @endif

    @if($type == 'shipping_label')
        <div style="border: 2px dashed #000; padding: 20px; margin-top: 20px; text-align: center;">
             <h2>ETIQUETA DE ENVÍO</h2>
             <div style="font-size: 1.5em; font-weight: bold; margin: 20px 0;">
                {{ $pedido->numero_pedido }}
             </div>
             <p>Este documento sirve como referencia interna para el envío.</p>
        </div>
    @endif

    <div class="footer">
        Generado el {{ date('d/m/Y H:i') }} - U-Key Admin Panel
    </div>

</body>
</html>
