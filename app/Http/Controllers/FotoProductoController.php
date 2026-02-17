<?php

namespace App\Http\Controllers;

use App\Models\FotoProducto;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FotoProductoController extends Controller
{
    /**
     * Servir imagen BLOB desde la BD
     */
    public function servir(FotoProducto $foto)
    {
        // Cargar con BLOB data usando el scope
        $foto = FotoProducto::withBlobData()->find($foto->id);
        
        if (!$foto || !$foto->datos_imagen) {
            abort(404);
        }
        
        // Decodificar base64 a datos binarios
        $datosDecoded = base64_decode($foto->datos_imagen);
        
        return response($datosDecoded)
            ->header('Content-Type', 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=31536000')
            ->header('Pragma', 'public');
    }

    /**
     * Delete a specific photo
     */
    public function destroy(FotoProducto $foto)
    {
        // Verificar que el usuario sea admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }

        // Con BLOB, el dato binario se elimina automáticamente al eliminar el registro
        // Por compatibilidad, eliminar también archivos si existen en el sistema
        if ($foto->nombre_archivo && Storage::disk('public')->exists('productos/' . $foto->nombre_archivo)) {
            Storage::disk('public')->delete('productos/' . $foto->nombre_archivo);
        }

        // Eliminar el registro (BLOB se elimina con el registro)
        $foto->delete();

        return redirect()->back()->with('success', 'Foto eliminada exitosamente');
    }
}
