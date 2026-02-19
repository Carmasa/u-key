<?php

namespace App\Http\Controllers;

use App\Models\FotoProducto;
use App\Models\Usuario;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FotoProductoController extends Controller
{
    /**
     * ✅ ENDPOINT: Servir imagen desde BLOB
     * GET /img/foto/{id}
     * 
     * Flujo:
     * 1. Carga foto con base64
     * 2. Decodifica base64 → bytes binarios
     * 3. Devuelve como imagen JPEG
     */
    public function servir(FotoProducto $foto)
    {
        // Paso 1: Cargar SOLO cuando se solicita explícitamente
        $foto = FotoProducto::withBlobData()->find($foto->id);
        
        if (!$foto || !$foto->datos_imagen) {
            abort(404);
        }
        
        // Paso 2: Decodificar base64 a bytes binarios
        $datosDecoded = base64_decode($foto->datos_imagen);
        
        // Paso 3: Retornar con headers HTTP correctos
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
        if (!Auth::check() || Auth::user()->rol !== 'Admin') {
            abort(403);
        }

        // Por compatibilidad, eliminar también archivos si existen
        if ($foto->nombre_archivo && Storage::disk('public')->exists('productos/' . $foto->nombre_archivo)) {
            Storage::disk('public')->delete('productos/' . $foto->nombre_archivo);
        }

        // ✅ BLOB se elimina automáticamente con el registro (cascade)
        $foto->delete();

        return redirect()->back()->with('success', 'Foto eliminada exitosamente');
    }
}

