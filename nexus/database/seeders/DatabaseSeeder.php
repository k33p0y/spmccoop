<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ElectionSeeder;
use Database\Seeders\PositionSeeder;
use Database\Seeders\ElectionDetailSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        // $this->call(ElectionSeeder::class);
        // $this->call(PositionSeeder::class);
        // $this->call(ElectionDetailSeeder::class);
        // $this->call(VoteSeeder::class);
    }
}
