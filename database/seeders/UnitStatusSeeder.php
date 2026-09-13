<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UnitStatusSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $rows = [
            ['id' => 1, 'status_name' => 'Available', 'description' => 'Unit is available', 'color_code' => '#28a745', 'sort_order' => 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'status_name' => 'Occupied', 'description' => 'Unit is occupied', 'color_code' => '#dc3545', 'sort_order' => 2, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'status_name' => 'Reserved', 'description' => 'Unit is reserved', 'color_code' => '#ffc107', 'sort_order' => 3, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('unit_statuses')->upsert($rows, ['id']);
    }
}
