<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    //Obtener el session_id del carrito
    private function getSessionId()
    {
        return session()->getId();
    }

    //Obtener items del carrito actual
    private function getCarritoItems()
    {
        return Carrito::where('session_id', $this->getSessionId())
            ->with('producto.categoria')
            ->get();
    }

    //Mostrar página de checkout
    public function index()
    {
        $items = $this->getCarritoItems();

        if ($items->isEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        // Verificar stock de todos los productos
        foreach ($items as $item) {
            if ($item->cantidad > $item->producto->stock) {
                return redirect()->route('carrito.index')
                    ->with('error', "No hay suficiente stock de {$item->producto->nombre}. Disponible: {$item->producto->stock}");
            }
        }

        $subtotal = $items->sum(fn($item) => $item->producto->precio * $item->cantidad);
        $envio = $subtotal >= 50 ? 0 : 4.99;
        $total = $subtotal + $envio;

        return view('checkout.index', compact('items', 'subtotal', 'envio', 'total'));
    }

    // Procesar el checkout y crear sesión de Stripe
    public function procesar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:500',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email no es válido.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'direccion.required' => 'La dirección de envío es obligatoria.',
        ]);

        $items = $this->getCarritoItems();

        if ($items->isEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        // Verificar stock nuevamente
        foreach ($items as $item) {
            if ($item->cantidad > $item->producto->stock) {
                return redirect()->route('carrito.index')
                    ->with('error', "No hay suficiente stock de {$item->producto->nombre}.");
            }
        }

        $subtotal = $items->sum(fn($item) => $item->producto->precio * $item->cantidad);
        $envio = $subtotal >= 50 ? 0 : 4.99;
        $total = $subtotal + $envio;

        // Preparar line_items para Stripe
        $lineItems = [];
        foreach ($items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->producto->nombre,
                        'description' => $item->producto->categoria->nombre,
                    ],
                    'unit_amount' => (int) ($item->producto->precio * 100), // Stripe usa céntimos
                ],
                'quantity' => $item->cantidad,
            ];
        }

        // Añadir envío si aplica
        if ($envio > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Gastos de envío',
                    ],
                    'unit_amount' => (int) ($envio * 100),
                ],
                'quantity' => 1,
            ];
        }

        // Guardar productos en formato JSON
        $productosJson = $items->map(fn($item) => [
            'id' => $item->producto->id,
            'nombre' => $item->producto->nombre,
            'precio' => $item->producto->precio,
            'cantidad' => $item->cantidad,
        ])->toArray();

        // Crear pedido pendiente
        $pedido = Pedido::create([
            'numero_pedido' => Pedido::generarNumeroPedido(),
            'usuario_id' => Auth::id(),
            'subtotal' => $subtotal,
            'envio' => $envio,
            'total' => $total,
            'estado' => 'pendiente',
            'session_id' => $this->getSessionId(),
            'nombre_cliente' => $request->nombre,
            'email_cliente' => $request->email,
            'telefono_cliente' => $request->telefono,
            'direccion_envio' => $request->direccion,
            'productos' => $productosJson,
        ]);

        try {
            // Crear sesión de Stripe Checkout
            $checkoutSession = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.exito', ['pedido' => $pedido->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancelar', ['pedido' => $pedido->id]),
                'customer_email' => $request->email,
                'metadata' => [
                    'pedido_id' => $pedido->id,
                    'numero_pedido' => $pedido->numero_pedido,
                ],
            ]);

            // Guardar ID de sesión de Stripe
            $pedido->update(['stripe_session_id' => $checkoutSession->id]);

            return redirect($checkoutSession->url);

        } catch (\Exception $e) {
            // Si falla, eliminar el pedido
            $pedido->delete();
            
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    // Página de éxito después del pago
    public function exito(Request $request, Pedido $pedido)
    {
        // Verificar que el pedido pertenece a esta sesión o usuario
        if ($pedido->session_id !== $this->getSessionId() && $pedido->usuario_id !== Auth::id()) {
            abort(403);
        }

        // Verificar con Stripe que el pago fue exitoso
        if ($request->has('session_id')) {
            try {
                $session = StripeSession::retrieve($request->session_id);
                
                if ($session->payment_status === 'paid') {
                    // Actualizar pedido
                    $pedido->update([
                        'estado' => \App\Models\Pedido::ESTADO_NUEVO,
                        'stripe_payment_intent' => $session->payment_intent,
                    ]);

                    // Reducir stock de productos
                    foreach ($pedido->productos as $producto) {
                        Producto::where('id', $producto['id'])
                            ->decrement('stock', $producto['cantidad']);
                    }

                    // Vaciar carrito
                    Carrito::where('session_id', $this->getSessionId())->delete();
                }
            } catch (\Exception $e) {
                // Log error pero mostrar página de éxito igualmente
                Log::error('Error verificando pago Stripe: ' . $e->getMessage());
            }
        }

        return view('checkout.exito', compact('pedido'));
    }

    //Página de cancelación
    public function cancelar(Pedido $pedido)
    {
        // Verificar que el pedido pertenece a esta sesión
        if ($pedido->session_id !== $this->getSessionId() && $pedido->usuario_id !== Auth::id()) {
            abort(403);
        }

        // Eliminar pedido cancelado
        $pedido->delete();

        return redirect()->route('carrito.index')
            ->with('info', 'El pago ha sido cancelado. Tu carrito sigue disponible.');
    }
}
