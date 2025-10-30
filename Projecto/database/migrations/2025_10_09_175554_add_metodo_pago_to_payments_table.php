<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('metodo_pago')->nullable()->after('amount');
            $table->string('mercadopago_id')->nullable()->after('id'); // For Mercado Pago
            $table->unsignedBigInteger('session_id')->nullable()->after('metodo_pago'); // Link to session
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['metodo_pago', 'mercadopago_id', 'session_id']);
        });
    }
};