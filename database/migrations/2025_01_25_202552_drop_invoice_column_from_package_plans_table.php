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
            // Drop the 'invoice' column
            $table->dropColumn('invoice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_plans', function (Blueprint $table) {
            // Optionally, re-add the 'invoice' column if rolling back the migration
            $table->string('invoice')->nullable(); // Adjust the type and nullable as per your original column
        });
    }
};
