<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fotos_productos', function (Blueprint $table) {
            // Columna LONGBLOB para almacenar los datos binarios de la imagen
            $table->longText('datos_imagen')->nullable()->after('nombre_archivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fotos_productos', function (Blueprint $table) {
            $table->dropColumn('datos_imagen');
        });
    }
};
