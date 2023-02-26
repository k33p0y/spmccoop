<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ElectionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('election_details')->insert([
            [
                'election_id' => 1, //1
                'position_id' => 1,
                'candidate_user_id' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'election_id' => 1, //2
                'position_id' => 1,
                'candidate_user_id' => 2,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'election_id' => 1, //3
                'position_id' => 1,
                'candidate_user_id' => 3,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'election_id' => 1, //4
                'position_id' => 2,
                'candidate_user_id' => 4,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'election_id' => 1, //5
                'position_id' => 2,
                'candidate_user_id' => 5,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'election_id' => 1, //6
                'position_id' => 3,
                'candidate_user_id' => 6,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'election_id' => 1, //7
                'position_id' => 3,
                'candidate_user_id' => 7,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'election_id' => 1, //8
                'position_id' => 3,
                'candidate_user_id' => 8,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'election_id' => 1, //9
                'position_id' => 3,
                'candidate_user_id' => 9,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //10
                'position_id' => 1,
                'candidate_user_id' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //11
                'position_id' => 1,
                'candidate_user_id' => 2,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //12
                'position_id' => 1,
                'candidate_user_id' => 3,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //13
                'position_id' => 2,
                'candidate_user_id' => 4,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //14
                'position_id' => 2,
                'candidate_user_id' => 5,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //15
                'position_id' => 2,
                'candidate_user_id' => 6,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //16
                'position_id' => 3,
                'candidate_user_id' => 7,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //17
                'position_id' => 3,
                'candidate_user_id' => 8,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //18
                'position_id' => 3,
                'candidate_user_id' => 9,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //19
                'position_id' => 4,
                'candidate_user_id' => 10,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //20
                'position_id' => 4,
                'candidate_user_id' => 11,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //21
                'position_id' => 4,
                'candidate_user_id' => 12,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //22
                'position_id' => 5,
                'candidate_user_id' => 13,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //23
                'position_id' => 5,
                'candidate_user_id' => 14,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //24
                'position_id' => 5,
                'candidate_user_id' => 15,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //25
                'position_id' => 6,
                'candidate_user_id' => 16,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //26
                'position_id' => 6,
                'candidate_user_id' => 17,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'election_id' => 2, //27
                'position_id' => 6,
                'candidate_user_id' => 18,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
