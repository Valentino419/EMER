<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::table('streets', function (Blueprint $table) {
            $table->string('start_number')->change(); // Cambia tipo (debe ir antes del rename)
        });

        Schema::table('streets', function (Blueprint $table) {
            $table->renameColumn('start_number', 'start_street');

        });
          Schema::table('streets', function (Blueprint $table) {
            $table->string('end_number')->change(); // Cambia tipo (debe ir antes del rename)
        });

        Schema::table('streets', function (Blueprint $table) {
            $table->renameColumn('end_number', 'end_street');
        });
    }

    public function down(): void
    {
        Schema::table('streets', function (Blueprint $table) {
            $table->renameColumn('end_street', 'end_number');
        });

        Schema::table('streets', function (Blueprint $table) {
            $table->integer('end_number')->change();
        });
        Schema::table('streets', function (Blueprint $table) {
            $table->renameColumn('start_street', 'start_number');
        });

        Schema::table('streets', function (Blueprint $table) {
            $table->integer('start_number')->change();
        });
    }
};
