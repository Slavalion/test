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
        Schema::table('review_reactions', function (Blueprint $table) {
            $table->unsignedBigInteger('task_id')->after('id');
            $table->unsignedBigInteger('review_reaction_group_id')->after('task_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_reactions', function (Blueprint $table) {
            $table->dropColumn('task_id');
        });
    }
};
