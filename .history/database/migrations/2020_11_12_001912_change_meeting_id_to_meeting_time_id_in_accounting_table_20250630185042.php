<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMeetingIdToMeetingTimeIdInAccountingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting', function (Blueprint $table) {
            // Check if foreign key exists before dropping it
            $foreignKeyExists = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'accounting' AND CONSTRAINT_NAME = 'accounting_meeting_id_foreign'");

            if (!empty($foreignKeyExists)) {
                DB::statement("ALTER TABLE `accounting` DROP FOREIGN KEY `accounting_meeting_id_foreign`;");
            }

            // Check if column exists before changing it
            $columnExists = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'accounting' AND COLUMN_NAME = 'meeting_id'");

            if (!empty($columnExists)) {
                DB::statement("ALTER TABLE `accounting` CHANGE COLUMN  `meeting_id` `meeting_time_id` INTEGER UNSIGNED NULL");
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting', function (Blueprint $table) {
            //
        });
    }
}
