<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of all productos (admin view).
     */
    public function index()
    {
        $query = Producto::with('categoria');

        // Filtro por nombre
        if (request('nombre')) {
            $query->where('nombre', 'like', '%' . request('nombre') . '%');
        }

        // Filtro por categoría
        if (request('categoria_id')) {
            $query->where('categoria_id', request('categoria_id'));
        }

        // Filtro por destacado
        if (request('destacado') !== null && request('destacado') !== '') {
            $query->where('destacado', request('destacado'));
        }

        $productos = $query->paginate(10);
        $categorias = Categoria::all();
        
        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    /**
     * Show the form for creating a new producto.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('admin.productos.create', compact('categorias'));
    }

    /**
     * Store a newly created producto in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        $validated = $request->validated();

        // Generar slug desde el nombre
        $validated['slug'] = Str::slug($validated['nombre']);

        // Manejar la imagen si se carga
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('productos', 'public');
            $validated['imagen'] = basename($imagenPath);
        }

        // Convertir el checkbox destacado a booleano
        $validated['destacado'] = (bool) $request->input('destacado', 0);

        Producto::create($validated);

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    /**
     * Show the form for editing the specified producto.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified producto in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $validated = $request->validated();

        // Actualizar slug
        $validated['slug'] = Str::slug($validated['nombre']);

        // Manejar la nueva imagen si se carga
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete('productos/' . $producto->imagen);
            }
            
            $imagenPath = $request->file('imagen')->store('productos', 'public');
            $validated['imagen'] = basename($imagenPath);
        }

        // Convertir el checkbox destacado a booleano
        $validated['destacado'] = (bool) $request->input('destacado', 0);

        $producto->update($validated);

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Remove the specified producto from storage.
     */
    public function destroy(Producto $producto)
    {
        // Eliminar imagen si existe
        if ($producto->imagen) {
            Storage::disk('public')->delete('productos/' . $producto->imagen);
        }

        $producto->delete();

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto eliminado exitosamente');
    }
}
