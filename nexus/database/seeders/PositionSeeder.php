<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->insert([
            [
                'name' => 'Board of Directors',
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Audit Committee',
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Election Committee',
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            // [
            //     'name' => 'Treasurer',
            //     'created_by' => 1,
            //     'created_at' => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'name' => 'Auditor',
            //     'created_by' => 1,
            //     'created_at' => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'name' => 'Liaison',
            //     'created_by' => 1,
            //     'created_at' => date('Y-m-d H:i:s'),
            // ]
        ]);
    }
}
