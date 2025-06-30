<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class EditDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            // Check if name column exists before dropping it
            $nameColumnExists = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'discounts' AND COLUMN_NAME = 'name'");
            if (!empty($nameColumnExists)) {
                DB::statement("ALTER TABLE `discounts` DROP COLUMN `name`");
            }

            // Check if count column exists in discount_users table before dropping it
            $countColumnExists = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'discount_users' AND COLUMN_NAME = 'count'");
            if (!empty($countColumnExists)) {
                DB::statement("ALTER TABLE `discount_users` DROP COLUMN `count`");
            }

            // Check if started_at column exists before dropping it
            $startedAtColumnExists = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'discounts' AND COLUMN_NAME = 'started_at'");
            if (!empty($startedAtColumnExists)) {
                DB::statement("ALTER TABLE `discounts` DROP COLUMN `started_at`");
            }

            // Modify created_at column
            DB::statement("ALTER TABLE `discounts` MODIFY COLUMN `created_at` int(0) UNSIGNED NOT NULL AFTER `expired_at`;");

            // Check if title column already exists before adding it
            $titleColumnExists = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'discounts' AND COLUMN_NAME = 'title'");
            if (empty($titleColumnExists)) {
                $table->string('title')->after('creator_id');
            }

            // Check if code column already exists before adding it
            $codeColumnExists = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'discounts' AND COLUMN_NAME = 'code'");
            if (empty($codeColumnExists)) {
                $table->string('code', 64)->after('title')->unique();
            }

            // Check if type column already exists before adding it
            $typeColumnExists = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'discounts' AND COLUMN_NAME = 'type'");
            if (empty($typeColumnExists)) {
                $table->enum('type', ['all_users', 'special_users'])->after('count');
            }
        });
    }
}
