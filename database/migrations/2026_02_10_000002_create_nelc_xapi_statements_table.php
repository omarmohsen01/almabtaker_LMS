<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nelc_xapi_statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('verb', 50);
            $table->string('object_type', 50);
            $table->unsignedBigInteger('object_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('lrs_response_uuid')->nullable();
            $table->integer('status_code')->nullable();
            $table->timestamp('sent_at')->nullable();

            $table->index(['user_id', 'verb', 'object_type', 'object_id', 'course_id'], 'nelc_xapi_unique_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('nelc_xapi_statements');
    }
};
