<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('first_team_en');
            $table->string('first_team_ar');
            $table->string('seconed_team_en');
            $table->string('seconed_team_ar');
            $table->string('day');
            $table->string('time');
            $table->string('stadium_en');
            $table->string('stadium_ar');
            $table->string('compitation_en');
            $table->string('compitation_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->integer('quantity')->default(1);
            $table->float('price');
            $table->string('seat_image')->nullable();
            $table->string('ticket_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
