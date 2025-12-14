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
        if (Schema::hasColumn('orders', 'customer_id')) {
            try {
                // Modify column to allow NULL using raw SQL to avoid doctrine/dbal dependency
                DB::statement("ALTER TABLE `orders` MODIFY `customer_id` BIGINT(20) UNSIGNED NULL;");
            } catch (\Throwable $e) {
                // ignore — best effort
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('orders', 'customer_id')) {
            try {
                // Attempt to revert to NOT NULL; will fail if NULLs exist, so wrapped in try
                DB::statement("ALTER TABLE `orders` MODIFY `customer_id` BIGINT(20) UNSIGNED NOT NULL;");
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }
};
