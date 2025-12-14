<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('orders', 'user_id')) {
            try {
                DB::statement("ALTER TABLE `orders` MODIFY `user_id` BIGINT(20) UNSIGNED NULL;");
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('orders', 'user_id')) {
            try {
                DB::statement("ALTER TABLE `orders` MODIFY `user_id` BIGINT(20) UNSIGNED NOT NULL;");
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }
};
