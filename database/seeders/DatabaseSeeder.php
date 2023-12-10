<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        # admin
        \App\Models\User::factory(1)->create(['role_id'=>1]);
        # user biasa
        \App\Models\User::factory(3)->create();
        # staff 1
        \App\Models\User::factory(2)->create(['role_id'=>2]);
        # staff 2
        \App\Models\User::factory(2)->create(['role_id'=>3]);
    }
}
