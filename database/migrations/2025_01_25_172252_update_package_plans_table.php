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
            $table->dropColumn('package_credits'); // Remove the `package_credits` column
            $table->integer('property_limit')->default(0)->after('invoice'); // Add `property_limit` column with a default value of 0
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_plans', function (Blueprint $table) {
            $table->string('package_credits')->nullable(); // Restore the `package_credits` column
            $table->dropColumn('property_limit'); // Remove the `property_limit` column
        });
    }
};
