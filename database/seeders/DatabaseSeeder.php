<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user1 = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);

        $user = User::factory()->create([
            'name' => 'Test',
            'email' => 'test@gmail.com',
        ]);
        $this->call([
            BloodGroupSeeder::class,
            GenderSeeder::class,
            TitleSeeder::class,
            ScanSeeder::class
        ]);

        $role = Role::create(['name' => 'Admin']);
        $user1->assignRole($role);
        // $user->assignRole($role);


    }
}
