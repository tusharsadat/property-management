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
        Schema::table('package_plans', function (Blueprint $table) {
            // Drop the foreign key constraint if it exists
            $table->dropForeign(['user_id']);  // Replace with the actual column name

            // Now drop the column itself
            $table->dropColumn('user_id');  // Replace with the actual column name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_plans', function (Blueprint $table) {
            // Optionally, re-add the foreign key and column if you want to reverse the migration
            $table->foreignId('user_id')->constrained();
        });
    }
};
