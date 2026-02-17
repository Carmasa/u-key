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
        $query = Producto::with('categoria', 'fotos');

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

        // Filtro por stock
        if (request('stock') !== null && request('stock') !== '') {
            if (request('stock') === 'sin_stock') {
                $query->where('stock', '=', 0);
            } elseif (request('stock') === 'con_stock') {
                $query->where('stock', '>', 0);
            }
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

        // Convertir los checkboxes a booleano
        // Con los hidden inputs, siempre se envían valores (0 o 1)
        $validated['destacado'] = (bool) $request->input('destacado', 0);
        $validated['visible'] = (bool) $request->input('visible', 0);

        // Crear el producto
        $producto = Producto::create($validated);

        // Manejar múltiples imágenes
        if ($request->hasFile('fotos')) {
            $orden = 0;
            foreach ($request->file('fotos') as $foto) {
                // Leer contenido y convertir a base64
                $contenidoBinario = file_get_contents($foto->getRealPath());
                $base64Encoded = base64_encode($contenidoBinario);
                
                $producto->fotos()->create([
                    'nombre_archivo' => $foto->getClientOriginalName(),
                    'datos_imagen' => $base64Encoded,
                    'orden' => $orden,
                    'principal' => $orden === 0,
                ]);
                $orden++;
            }
        }

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    /**
     * Show the form for editing the specified producto.
     */
    public function edit(Producto $producto)
    {
        $producto = $producto->load('fotos');
        
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

        // Convertir los checkboxes a booleano
        // Con los hidden inputs, siempre se envían valores (0 o 1)
        $validated['destacado'] = (bool) $request->input('destacado', 0);
        $validated['visible'] = (bool) $request->input('visible', 0);

        // Log para debuggear
        \Log::info('Actualizar producto', [
            'producto_id' => $producto->id,
            'destacado' => $request->input('destacado'),
            'visible' => $request->input('visible'),
            'validated_destacado' => $validated['destacado'],
            'validated_visible' => $validated['visible'],
        ]);

        $producto->update($validated);

        // Manejar múltiples imágenes
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                // Leer contenido y convertir a base64
                $contenidoBinario = file_get_contents($foto->getRealPath());
                $base64Encoded = base64_encode($contenidoBinario);
                
                $maxOrden = $producto->fotos()->max('orden') ?? 0;
                $producto->fotos()->create([
                    'nombre_archivo' => $foto->getClientOriginalName(),
                    'datos_imagen' => $base64Encoded,
                    'orden' => $maxOrden + 1,
                    'principal' => false,
                ]);
            }
        }

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Remove the specified producto from storage.
     */
    public function destroy(Producto $producto)
    {
        // Con BLOB, las imágenes se eliminan automáticamente al eliminar el registro
        // dependencia en la migración (onDelete('cascade'))
        
        // Por compatibilidad, podemos eliminar archivos antiguos si existen
        foreach ($producto->fotos as $foto) {
            // Si existe nombre_archivo en el sistema de archivos, eliminarlo (compatibilidad)
            if ($foto->nombre_archivo && Storage::disk('public')->exists('productos/' . $foto->nombre_archivo)) {
                Storage::disk('public')->delete('productos/' . $foto->nombre_archivo);
            }
        }

        // Eliminar imagen antigua si existe en storage (para compatibilidad)
        if ($producto->imagen && Storage::disk('public')->exists('productos/' . $producto->imagen)) {
            Storage::disk('public')->delete('productos/' . $producto->imagen);
        }

        // Eliminar el producto (y sus fotos relacionadas por cascade)
        $producto->delete();

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto eliminado exitosamente');
    }
}
