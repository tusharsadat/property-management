<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('package_plans')->insert([
            ['package_name' => 'Basic Package', 'property_limit' => 1, 'package_amount' => 0],
            ['package_name' => 'Business Package', 'property_limit' => 3, 'package_amount' => 10],
            ['package_name' => 'Professional Package', 'property_limit' => 10, 'package_amount' => 20],
        ]);
    }
}
