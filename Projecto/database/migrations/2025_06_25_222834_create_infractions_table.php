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
        Schema::create('infractions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // inspector
            $table->unsignedBigInteger('car_id');
            $table->integer('fine');
            $table->date('date');
            $table->string('status')->default('pendiente');
            $table->timestamps();

            
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infractions');
    }
};
