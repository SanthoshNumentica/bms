<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert scan types
        $scanTypes = [
            ['name' => 'MRI'],
            ['name' => 'X-Ray'],
            ['name' => 'CT Scan'],
        ];

        DB::table('scan_types')->insert($scanTypes);

        // Get inserted scan_type IDs
        $mriId = DB::table('scan_types')->where('name', 'MRI')->value('id');
        $xrayId = DB::table('scan_types')->where('name', 'X-Ray')->value('id');
        $ctId = DB::table('scan_types')->where('name', 'CT Scan')->value('id');

        // Insert scans related to each type
        $scans = [
            ['name' => 'Brain MRI', 'scan_type_fk_id' => $mriId],
            ['name' => 'Spine MRI', 'scan_type_fk_id' => $mriId],
            ['name' => 'Knee MRI', 'scan_type_fk_id' => $mriId],
            ['name' => 'Chest X-Ray', 'scan_type_fk_id' => $xrayId],
            ['name' => 'Abdominal X-Ray', 'scan_type_fk_id' => $xrayId],
            ['name' => 'Head CT', 'scan_type_fk_id' => $ctId],
            ['name' => 'Pelvis CT', 'scan_type_fk_id' => $ctId],
        ];

        DB::table('scans')->insert($scans);
    }
}
