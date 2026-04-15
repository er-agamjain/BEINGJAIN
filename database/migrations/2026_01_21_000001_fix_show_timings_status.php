<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix any existing show_timings with NULL or invalid status
        DB::table('show_timings')
            ->where('status', null)
            ->orWhere('status', 'active')
            ->update(['status' => 'scheduled']);
    }

    public function down(): void
    {
        // No need to rollback status changes
    }
};
