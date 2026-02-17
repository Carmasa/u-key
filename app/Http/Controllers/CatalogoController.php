<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        $productosDestacados = Producto::where('destacado', true)
            ->where('visible', true)
            ->with('categoria', 'fotos')
            ->paginate(12);

        return view('catalogo.index', compact('categorias', 'productosDestacados'));
    }


    public function porCategoria($slug)
    {
        $categoria = Categoria::where('slug', $slug)->firstOrFail();
        $categorias = Categoria::all();
        $productos = $categoria->productos()
            ->where('visible', true)
            ->with('fotos')
            ->paginate(12);

        return view('catalogo.categoria', compact('categoria', 'productos', 'categorias'));
    }


    public function detalle($id)
    {
        $producto = Producto::with('fotos')->findOrFail($id);
        
        if (!$producto->visible && auth()->guard('admin')->check() === false) {
            abort(404);
        }
        
        $categorias = Categoria::all();

        return view('catalogo.detalle', compact('producto', 'categorias'));
    }
}
