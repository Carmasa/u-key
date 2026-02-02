<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    /**
     * Obtener el session_id del carrito
     */
    private function getSessionId()
    {
        return session()->getId();
    }

    /**
     * Obtener items del carrito actual
     */
    private function getCarritoItems()
    {
        return Carrito::where('session_id', $this->getSessionId())
            ->with('producto.categoria')
            ->get();
    }

    /**
     * Calcular totales del carrito
     */
    private function calcularTotales($items)
    {
        $subtotal = $items->sum(function ($item) {
            return $item->producto->precio * $item->cantidad;
        });

        $envio = $subtotal >= 50 ? 0 : 4.99;
        $total = $subtotal + $envio;

        return [
            'subtotal' => $subtotal,
            'envio' => $envio,
            'envio_gratis' => $subtotal >= 50,
            'faltan_para_envio_gratis' => max(0, 50 - $subtotal),
            'total' => $total,
            'num_items' => $items->sum('cantidad')
        ];
    }

    /**
     * Mostrar el carrito
     */
    public function index()
    {
        $items = $this->getCarritoItems();
        $totales = $this->calcularTotales($items);

        return view('carrito.index', compact('items', 'totales'));
    }

    /**
     * Añadir producto al carrito
     */
    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ], [
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.'
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        // Verificar que el producto esté visible
        if (!$producto->visible) {
            return back()->with('error', 'Este producto no está disponible.');
        }

        // Buscar si ya existe en el carrito
        $itemCarrito = Carrito::where('session_id', $this->getSessionId())
            ->where('producto_id', $producto->id)
            ->first();

        $cantidadTotal = $request->cantidad;
        if ($itemCarrito) {
            $cantidadTotal += $itemCarrito->cantidad;
        }

        // Verificar stock disponible
        if ($cantidadTotal > $producto->stock) {
            $disponible = $producto->stock - ($itemCarrito ? $itemCarrito->cantidad : 0);
            if ($disponible <= 0) {
                return back()->with('error', 'No hay más stock disponible de este producto.');
            }
            return back()->with('error', "Solo puedes añadir {$disponible} unidad(es) más. Stock limitado.");
        }

        if ($itemCarrito) {
            // Actualizar cantidad
            $itemCarrito->update(['cantidad' => $cantidadTotal]);
        } else {
            // Crear nuevo item
            Carrito::create([
                'producto_id' => $producto->id,
                'cantidad' => $request->cantidad,
                'session_id' => $this->getSessionId()
            ]);
        }

        return back()->with('success', '¡Producto añadido al carrito!');
    }

    /**
     * Actualizar cantidad de un item
     */
    public function actualizar(Request $request, Carrito $carrito)
    {
        // Verificar que el item pertenece a esta sesión
        if ($carrito->session_id !== $this->getSessionId()) {
            abort(403);
        }

        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ], [
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.'
        ]);

        // Verificar stock
        if ($request->cantidad > $carrito->producto->stock) {
            return back()->with('error', "Solo hay {$carrito->producto->stock} unidades disponibles.");
        }

        $carrito->update(['cantidad' => $request->cantidad]);

        return back()->with('success', 'Cantidad actualizada.');
    }

    /**
     * Eliminar item del carrito
     */
    public function eliminar(Carrito $carrito)
    {
        // Verificar que el item pertenece a esta sesión
        if ($carrito->session_id !== $this->getSessionId()) {
            abort(403);
        }

        $carrito->delete();

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    /**
     * Vaciar el carrito completo
     */
    public function vaciar()
    {
        Carrito::where('session_id', $this->getSessionId())->delete();

        return back()->with('success', 'Carrito vaciado.');
    }

    /**
     * Obtener el número de items en el carrito (para AJAX)
     */
    public function contador()
    {
        $count = Carrito::where('session_id', $this->getSessionId())->sum('cantidad');
        return response()->json(['count' => $count]);
    }
}
