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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->date('from');
            $table->date('to');
            $table->integer('maximum_times_of_use');
            $table->integer('number_of_times_used')->default(0);
            $table->enum('dedicated_to',['all','female','male'])->default('all');
            $table->enum('type', ['percent', 'amount'])->default('percent');
            $table->enum('status',['1','0'])->default('1');
            $table->float('value');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};