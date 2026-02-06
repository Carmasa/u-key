<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class UserPedidoController extends Controller
{
    public function index()
    {
        $pedidos = \App\Models\Pedido::where('usuario_id', Auth::id())
            ->latest()
            ->get();
            
        return view('user.pedidos.index', compact('pedidos'));
    }
}
