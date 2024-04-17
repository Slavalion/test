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
            $table->tinyInteger('picked_up_status')->default(0)->after('purchase_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livecargo_deliveries', function (Blueprint $table) {
            $table->dropColumn('picked_up_status');
        });
    }
};
