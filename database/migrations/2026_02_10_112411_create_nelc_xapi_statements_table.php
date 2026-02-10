<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nelc_xapi_statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('webinar_id')->nullable();
            $table->string('verb', 50)->index();
            $table->string('object_type', 50);
            $table->string('object_id', 255);
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->text('response_body')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'verb', 'object_id'], 'nelc_xapi_unique_statement');
            $table->index(['user_id', 'webinar_id']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nelc_xapi_statements');
    }
};
