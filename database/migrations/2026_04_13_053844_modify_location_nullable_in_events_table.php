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
        Schema::table('events', function (Blueprint $table) {
            $table->string('location')->nullable()->change();
            if (!Schema::hasColumn('events', 'all_day')) {
                $table->boolean('all_day')->default(false)->after('location');
            }
            if (!Schema::hasColumn('events', 'navigation_location')) {
                $table->string('navigation_location')->nullable()->after('location');
            }
            if (!Schema::hasColumn('events', 'address')) {
                $table->string('address', 500)->nullable()->after('navigation_location');
            }
            if (!Schema::hasColumn('events', 'city_id')) {
                $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null')->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn(['city_id', 'address', 'navigation_location', 'all_day']);
            $table->string('location')->nullable(false)->change();
        });
    }
};
