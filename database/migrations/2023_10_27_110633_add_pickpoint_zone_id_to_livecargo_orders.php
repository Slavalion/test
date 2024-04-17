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
        Schema::table('livecargo_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('pickpoint_zone_id')->after('order_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livecargo_orders', function (Blueprint $table) {
            $table->dropColumn('pickpoint_zone_id');
        });
    }
};
