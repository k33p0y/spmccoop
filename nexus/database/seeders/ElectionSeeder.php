<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('elections')->insert([
            [
                'name' => 'Halalan 2022',
                'status' => 0,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'name' => 'Election 2023',
                'status' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
        ]);
    }
}
