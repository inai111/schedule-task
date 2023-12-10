<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'description' => 'WO Manager'
            ],
            [
                'name' => 'staff_lapangan',
                'description' => 'WO Staff Lapangan'
            ],
            [
                'name' => 'staff_pembayaran',
                'description' => 'WO Staff Pembarayan'
            ],
            [
                'name' => 'member',
                'description' => 'WO Client'
            ],
        ]);
    }
}
