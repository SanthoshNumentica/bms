<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = ['Mr.', 'Mrs.', 'Miss', 'Dr.', 'Prof.'];

        foreach ($titles as $title) {
            DB::table('titles')->insert([
                'title_name' => $title,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
