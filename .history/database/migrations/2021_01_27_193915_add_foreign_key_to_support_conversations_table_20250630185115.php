<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToSupportConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('support_conversations', function (Blueprint $table) {
            // Check if foreign key for support_id exists before adding it
            $supportFkExists = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'support_conversations' AND CONSTRAINT_NAME = 'support_conversations_support_id_foreign'");

            if (empty($supportFkExists)) {
                $table->foreign('support_id')->on('supports')->references('id')->onDelete('cascade');
            }

            // Check if foreign key for sender_id exists before adding it
            $senderFkExists = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'support_conversations' AND CONSTRAINT_NAME = 'support_conversations_sender_id_foreign'");

            if (empty($senderFkExists)) {
                $table->foreign('sender_id')->on('users')->references('id')->onDelete('cascade');
            }
        });
    }
}
