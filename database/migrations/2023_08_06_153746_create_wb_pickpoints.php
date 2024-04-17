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
        Schema::create('wb_pickpoints', function (Blueprint $table) {
            $table->id();
            $table->string('pickpoint_id');
            $table->string('coordinates');
            $table->string('address');
            $table->string('work_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wb_pickpoints');
    }
};
