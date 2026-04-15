<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add show_timing_id to bookings table
        if (!Schema::hasColumn('bookings', 'show_timing_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreignId('show_timing_id')->nullable()->constrained('show_timings')->onDelete('cascade')->after('event_id');
            });
        }

        // Create booking_seat pivot table
        Schema::create('booking_seat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('seat_id')->constrained('seats')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['booking_id', 'seat_id']);
            $table->index('seat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_seat');
        
        if (Schema::hasColumn('bookings', 'show_timing_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeignIdFor('show_timing_id');
                $table->dropColumn('show_timing_id');
            });
        }
    }
};
