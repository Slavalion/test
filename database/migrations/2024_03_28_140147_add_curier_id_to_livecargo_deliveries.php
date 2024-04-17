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
            $table->unsignedBigInteger('courier_id')->nullable()->default(null)->after('picked_up_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livecargo_deliveries', function (Blueprint $table) {
            $table->dropColumn('courier_id');
        });
    }
};
