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
        Schema::create('parking_sessions', function (Blueprint $table) {
            $table->id();

            // User and Car relationships
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');

            // Location relationships
            $table->foreignId('zone_id')->nullable()->constrained('zones')->onDelete('set null');
            $table->foreignId('street_id')->nullable()->constrained('streets')->onDelete('set null');

            // Vehicle identification (for quick lookup)
            $table->string('license_plate', 10)->index();

            // Time tracking (stored in UTC, timezone handled in app)
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('duration')->nullable(); // In minutes

            // Financial data
            $table->decimal('rate', 8, 2)->nullable(); // Rate per hour
            $table->decimal('amount', 10, 2)->nullable(); // Total amount for session
            $table->string('payment_id')->nullable()->unique(); // Stripe PaymentIntent ID

            // Status tracking
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
            $table->string('metodo_pago', 20)->nullable()->default('tarjeta'); // 'tarjeta', 'efectivo', etc.

            // Timestamps
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['car_id', 'status']);
            $table->index('payment_status');
            $table->index('start_time');
            $table->index('end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_sessions');
    }
};