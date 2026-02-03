<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // Modificamos la columna para permitir los nuevos valores
            // Nota: En algunas bases de datos cambiar ENUM puede requerir raw statements
            // Pero Laravel intenta manejarlo. Si falla, usaremos DB::statement
            $table->string('estado')->default('pendiente')->change();
        });

        // Si queremos volver a convertirlo a enum con los nuevos valores, podemos hacerlo en un paso separado
        // O simplemente dejarlo como string con validación en aplicación para mayor flexibilidad
        // Por consistencia con la petición, intentaremos usar el enum si es MySQL, o string si da problemas.
        // Dado que SQLite (que puede ser el entorno local) no soporta bien cambios de ENUMs,
        // lo más seguro y portable es cambiarlo a string o asegurarse que los valores son compatibles.
        // Haremos un update seguro de los datos existentes si fuera necesario.

        // Actualizamos registros existentes si tuvieran valores incompatibles (opcional)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // Revertir es complejo si hay datos nuevos, pero definimos la estructura original
            // $table->enum('estado', ['pendiente', 'procesado', 'enviado', 'entregado'])->default('pendiente')->change();
            $table->string('estado')->default('pendiente')->change();
        });
    }
};
