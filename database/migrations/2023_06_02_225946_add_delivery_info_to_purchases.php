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
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('delivery_qrcode')->default('')->after('address');
            $table->string('delivery_pin', 30)->default('')->after('address');
            $table->string('delivery_status', 50)->default('pending')->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['delivery_status', 'delivery_pin', 'delivery_qrcode']);
        });
    }
};
