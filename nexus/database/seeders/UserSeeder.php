<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => 'Isabel',
                'name' => 'Isabel Bridges',
                'email' => 'p1@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 1,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Daisie',
                'name' => 'Daisie Wolf',
                'email' => 'p2@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 1,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Brogan',
                'name' => 'Brogan Mann',
                'email' => 'p3@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 1,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Alec',
                'name' => 'Alec Holt',
                'email' => 'p4@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 1,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Silas',
                'name' => 'Silas Acosta',
                'email' => 'p5@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 1,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Roosevelt',
                'name' => 'Roosevelt Bryant',
                'email' => 'p6@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Tahlia',
                'name' => 'Tahlia Boyd',
                'email' => 'p7@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Amber',
                'name' => 'Amber Bullock',
                'email' => 'p8@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Kelvin',
                'name' => 'Kelvin Holman',
                'email' => 'p9@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Ethan',
                'name' => 'Ethan Prince',
                'email' => 'p10@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Darius',
                'name' => 'Darius Higgins',
                'email' => 'p11@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Abi',
                'name' => 'Abi Berry',
                'email' => 'p12@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Milton',
                'name' => 'Milton Reese',
                'email' => 'p13@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Lukas',
                'name' => 'Lukas Roberts',
                'email' => 'p14@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Dhruv',
                'name' => 'Dhruv Walter',
                'email' => 'p15@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Reggie',
                'name' => 'Reggie Rojas',
                'email' => 'p16@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Yasmin',
                'name' => 'Yasmin Sharpe',
                'email' => 'p17@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Maddison',
                'name' => 'Maddison Ballard',
                'email' => 'p18@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'p19',
                'name' => 'Ebony Cook',
                'email' => 'p19@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], 
            [
                'username' => 'Frazer',
                'name' => 'Frazer Burgess',
                'email' => 'p20@gmail.com',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'candidate' => 1,
                'admin' => 0,
                'code' => $this->quickRandom(),
                'employee_id' => $this->quickRandom(),
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }

    private function quickRandom($length = 8) {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}