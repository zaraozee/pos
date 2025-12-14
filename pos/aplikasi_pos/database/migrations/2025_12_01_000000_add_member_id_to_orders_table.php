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
    {
        // Only add the column if it doesn't already exist to avoid errors
        if (! Schema::hasColumn('orders', 'member_id')) {
            // add nullable column first
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('member_id')->nullable()->after('user_id');
            });

            // if there's an existing customer_id column, copy values to member_id
            if (Schema::hasColumn('orders', 'customer_id')) {
                // use raw query to copy values
                try {
                    DB::update('UPDATE `orders` SET `member_id` = `customer_id` WHERE `member_id` IS NULL');
                } catch (\Throwable $e) {
                    // ignore copy failure
                }
            }

            // finally add foreign key constraint if members table exists
            if (Schema::hasTable('members')) {
                try {
                    Schema::table('orders', function (Blueprint $table) {
                        $table->foreign('member_id')->references('id')->on('members');
                    });
                } catch (\Throwable $e) {
                    // ignore FK add failure
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('orders', 'member_id')) {
            Schema::table('orders', function (Blueprint $table) {
                // Drop foreign key first if it exists, then drop column
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                // Attempt to drop constraint safely; ignore if not present
                try {
                    $table->dropForeign(['member_id']);
                } catch (\Throwable $e) {
                    // ignore
                }

                $table->dropColumn('member_id');
            });
        }
    }
};
