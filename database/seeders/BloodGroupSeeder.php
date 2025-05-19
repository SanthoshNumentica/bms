<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BloodGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        foreach ($bloodGroups as $group) {
            DB::table('blood_groups')->insert([
                'name' => $group,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
}
