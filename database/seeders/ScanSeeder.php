<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now(); 

        // Insert scan types with timestamps
        $scanTypes = [
            ['name' => 'MRI', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'X-Ray', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'CT Scan', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('scan_types')->insert($scanTypes);

        // Get inserted scan_type IDs
        $mriId = DB::table('scan_types')->where('name', 'MRI')->value('id');
        $xrayId = DB::table('scan_types')->where('name', 'X-Ray')->value('id');
        $ctId = DB::table('scan_types')->where('name', 'CT Scan')->value('id');

        // Insert scans with timestamps
        $scans = [
            ['name' => 'Brain MRI', 'scan_type_fk_id' => $mriId, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Spine MRI', 'scan_type_fk_id' => $mriId, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Knee MRI', 'scan_type_fk_id' => $mriId, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chest X-Ray', 'scan_type_fk_id' => $xrayId, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Abdominal X-Ray', 'scan_type_fk_id' => $xrayId, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Head CT', 'scan_type_fk_id' => $ctId, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pelvis CT', 'scan_type_fk_id' => $ctId, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('scans')->insert($scans);
    }
}
