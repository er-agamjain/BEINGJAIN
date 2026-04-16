<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // New nullable FK so a ticket can be scoped to a show timing
            $table->foreignId('show_timing_id')
                  ->nullable()
                  ->after('event_id')
                  ->constrained('show_timings')
                  ->onDelete('cascade');

            // Allow event_id to be null so future tickets are purely show-timing-scoped
            $table->foreignId('event_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['show_timing_id']);
            $table->dropColumn('show_timing_id');
            $table->foreignId('event_id')->nullable(false)->change();
        });
    }
};
