<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class AdminPedidoController extends Controller
{
    /**
     * Muestra la lista de pedidos con pestañas.
     */
    public function index(Request $request)
    {
        // Pestaña activa por defecto
        $tab = $request->get('tab', 'ultimos');
        $search = $request->get('search');

        // Consultas base
        $ultimos = Pedido::latest()->take(20)->get();
        $pendientes = Pedido::pendientes()->oldest()->get();
        
        // Consulta para "Todos" con búsqueda
        $todosQuery = Pedido::latest();
        
        if ($search) {
            $todosQuery->where('numero_pedido', 'like', "%{$search}%")
                       ->orWhere('nombre_cliente', 'like', "%{$search}%")
                       ->orWhere('email_cliente', 'like', "%{$search}%");
        }
        
        $todos = $todosQuery->paginate(15);

        // Contador para badges (recalculado para asegurar consistencia)
        $countNuevos = Pedido::nuevos()->count();

        // Marcar como vistos si estamos en la pestaña pendientes/ultimos y hay nuevos? 
        // Por simplificación, mantendremos el estado 'nuevo' hasta que el admin interactúe (cambie estado o entre al detalle).

        return view('admin.pedidos.index', compact('ultimos', 'pendientes', 'todos', 'tab', 'search', 'countNuevos'));
    }

    /**
     * Muestra el detalle de un pedido.
     */
    public function show($id)
    {
        $pedido = Pedido::findOrFail($id);
        
        // Si el pedido es "nuevo", lo pasamos a "pendiente" al verlo (opcional, o dejar manual)
        // Para este caso, el usuario pidió "Botón de estado...", así que mejor manual o al procesar.
        
        return view('admin.pedidos.show', compact('pedido'));
    }

    /**
     * Actualiza el estado del pedido.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,nuevo,preparacion,enviado'
        ]);

        $pedido = Pedido::findOrFail($id);
        $pedido->update(['estado' => $request->estado]);

        return redirect()->back()->with('success', 'Estado del pedido actualizado correctamente.');
    }

    /**
     * Genera vista de impresión para lista de artículos (Packing List).
     */
    public function downloadPackingList($id)
    {
        $pedido = Pedido::findOrFail($id);
        $type = 'packing_list'; // Para lógica en vista
        return view('admin.pedidos.print', compact('pedido', 'type'));
    }

    /**
     * Genera vista de impresión para etiqueta de envío.
     */
    public function downloadShippingLabel($id)
    {
        $pedido = Pedido::findOrFail($id);
        $type = 'shipping_label'; // Para lógica en vista
        return view('admin.pedidos.print', compact('pedido', 'type'));
    }
}
