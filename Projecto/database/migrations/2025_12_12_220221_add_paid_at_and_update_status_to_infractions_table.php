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
    {  // Change 'pendiente' → 'pending'
        DB::table('infractions')
            ->where('status', 'pendiente')
            ->update(['status' => 'pending']);

        // If you already have any 'pagada' values, map them too
        DB::table('infractions')
            ->where('status', 'pagada')
            ->update(['status' => 'paid']);
        Schema::table('infractions', function (Blueprint $table) {
            // Change status to enum with allowed values: pending, paid
        
            $table->enum('status', ['pending', 'paid'])
                  ->default('pending')
                  ->change();

            // Add paid_at timestamp (nullable)
            $table->timestamp('paid_at')->nullable()->after('status');

            // Added index for faster filtering by status
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infractions', function (Blueprint $table) {
            // Revert status back to string (original state)
            $table->string('status')->default('pendiente')->change();

            // Drop the paid_at column
            $table->dropColumn('paid_at');

            // Drop index if it exists
            $table->dropIndex(['status']);
        });
         DB::table('infractions')
            ->where('status', 'pending')
            ->update(['status' => 'pendiente']);

        DB::table('infractions')
            ->where('status', 'paid')
            ->update(['status' => 'pagada']);
    }
};
