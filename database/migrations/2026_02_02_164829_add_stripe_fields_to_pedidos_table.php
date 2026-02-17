<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->after('session_id');
            $table->string('stripe_payment_intent')->nullable()->after('stripe_session_id');
            $table->string('nombre_cliente')->nullable()->after('stripe_payment_intent');
            $table->string('email_cliente')->nullable()->after('nombre_cliente');
            $table->string('telefono_cliente')->nullable()->after('email_cliente');
            $table->text('direccion_envio')->nullable()->after('telefono_cliente');
            $table->decimal('subtotal', 10, 2)->default(0)->after('total');
            $table->decimal('envio', 10, 2)->default(0)->after('subtotal');
            $table->json('productos')->nullable()->after('envio');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropColumn([
                'stripe_session_id',
                'stripe_payment_intent',
                'nombre_cliente',
                'email_cliente',
                'telefono_cliente',
                'direccion_envio',
                'subtotal',
                'envio',
                'productos',
                'usuario_id'
            ]);
        });
    }
};
