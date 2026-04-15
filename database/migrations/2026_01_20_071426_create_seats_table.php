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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seat_category_id')->constrained('seat_categories')->onDelete('cascade');
            $table->foreignId('show_timing_id')->nullable()->constrained('show_timings')->onDelete('cascade');
            $table->string('seat_number'); // A1, A2, B1, etc.
            $table->integer('row_number');
            $table->integer('column_number');
            $table->enum('status', ['available', 'booked', 'blocked', 'reserved'])->default('available');
            $table->decimal('current_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
