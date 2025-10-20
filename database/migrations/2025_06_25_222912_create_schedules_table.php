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
     
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->json('days_of_week'); // Store array of days, e.g., ["Monday", "Tuesday"]
            $table->time('start_hour'); // e.g., 08:00:00
            $table->time('end_hour'); // e.g., 18:00:00
            $table->integer('rate'); // Rate in some currency unit
            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
