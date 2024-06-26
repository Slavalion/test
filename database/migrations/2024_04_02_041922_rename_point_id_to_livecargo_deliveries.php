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
        Schema::table('livecargo_deliveries', function (Blueprint $table) {
            $table->renameColumn('point_id', 'pickpoint_address_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livecargo_deliveries', function (Blueprint $table) {
            $table->renameColumn('pickpoint_address_id', 'point_id');
        });
    }
};
