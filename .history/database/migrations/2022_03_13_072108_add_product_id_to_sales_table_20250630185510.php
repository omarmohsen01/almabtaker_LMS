<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddProductIdToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Check if product_order_id column exists before adding it
            $productOrderIdExists = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sales' AND COLUMN_NAME = 'product_order_id'");

            if (empty($productOrderIdExists)) {
                $table->integer('product_order_id')->unsigned()->nullable()->after('promotion_id');
            }
        });

        // Update enum type after column is added
        DB::statement("ALTER TABLE `sales` MODIFY COLUMN `type` enum('webinar','meeting','subscribe','promotion','registration_package','product') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `registration_package_id`");

        Schema::table('order_items', function (Blueprint $table) {
            // Check if product_id column exists before adding it
            $productIdExists = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'order_items' AND COLUMN_NAME = 'product_id'");

            if (empty($productIdExists)) {
                $table->integer('product_id')->unsigned()->nullable()->after('registration_package_id');
            }

            // Check if product_order_id column exists before adding it
            $productOrderIdExists = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'order_items' AND COLUMN_NAME = 'product_order_id'");

            if (empty($productOrderIdExists)) {
                $table->integer('product_order_id')->unsigned()->nullable()->after('product_id');
            }
        });

        Schema::table('accounting', function (Blueprint $table) {
            // Check if product_id column exists before adding it
            $productIdExists = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'accounting' AND COLUMN_NAME = 'product_id'");

            if (empty($productIdExists)) {
                $table->integer('product_id')->unsigned()->nullable()->after('registration_package_id');
            }
        });
    }
}
