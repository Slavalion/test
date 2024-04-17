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
        Schema::create('livecargo_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->default('');
            $table->boolean('is_test')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->json('data')->default(null);
            $table->mediumText('message')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livecargo_orders');
    }
};
