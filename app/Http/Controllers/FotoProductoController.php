<?php

namespace App\Http\Controllers;

use App\Models\FotoProducto;
use Illuminate\Support\Facades\Storage;

class FotoProductoController extends Controller
{
    /**
     * Delete a specific photo
     */
    public function destroy(FotoProducto $foto)
    {
        // Verificar que el usuario sea admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }

        // Eliminar el archivo
        if ($foto->nombre_archivo) {
            Storage::disk('public')->delete('productos/' . $foto->nombre_archivo);
        }

        // Eliminar el registro
        $foto->delete();

        return redirect()->back()->with('success', 'Foto eliminada exitosamente');
    }
}
