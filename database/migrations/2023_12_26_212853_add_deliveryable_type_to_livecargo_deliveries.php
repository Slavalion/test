<?php

use App\Models\Purchase;
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
            $table->renameColumn('purchase_id', 'deliveryable_id');
            $table->string('deliveryable_type')->default(Purchase::class)->after('point_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livecargo_deliveries', function (Blueprint $table) {
            $table->renameColumn('deliveryable_id', 'purchase_id');
            $table->dropColumn('deliveryable_type');
        });
    }
};
