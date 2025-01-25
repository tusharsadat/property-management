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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('property_limit')->default(0)->after('status'); // Number of properties the user can add
            $table->integer('used_properties')->default(0)->after('property_limit'); // Track how many properties the user has already used
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('property_limit');
            $table->dropColumn('used_properties');
        });
    }
};
