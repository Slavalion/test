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
            $table->timestamp('status_updated_at')->nullable()->default(null)->after('courier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livecargo_deliveries', function (Blueprint $table) {
            $table->dropColumn('status_updated_at');
        });
    }
};
