<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = ['Male', 'Female', 'Other'];

        foreach ($genders as $gender) {
            DB::table('genders')->insert([
                'gender_name' => $gender,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
