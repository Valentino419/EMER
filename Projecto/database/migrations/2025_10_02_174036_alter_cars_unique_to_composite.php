<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Eliminar el índice único global en car_plate
            $table->dropUnique(['car_plate']); // Laravel infiere el nombre del índice como 'cars_car_plate_unique'
            
            // Añadir índice único compuesto: car_plate + user_id
            $table->unique(['car_plate', 'user_id'], 'cars_car_plate_user_unique');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Revertir: eliminar compuesto y añadir global
            $table->dropUnique(['car_plate', 'user_id']);
            $table->unique('car_plate', 'cars_car_plate_unique');
        });
    }
};
