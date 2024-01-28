<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Vendor;
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
            CategorySeeder::class,
            VendorSeeder::class,
        ]);

        # admin
        \App\Models\User::factory(1)->create([
            'role_id'=>1,
            'username'=>'admin'
        ]);
        # user biasa
        \App\Models\User::factory(3)->create();
        \App\Models\User::factory(1)->create(['username'=>'uuu','email'=>'sasmitobaguss@gmail.com']);
        # staff 1
        \App\Models\User::factory(2)->create(['role_id'=>2]);
        \App\Models\User::factory()->create([
            'username'=>'jay',
            'role_id'=>2
        ]);
        # staff 2
        \App\Models\User::factory(2)->create(['role_id'=>3]);
    }
}
