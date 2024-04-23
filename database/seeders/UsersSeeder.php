<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert($this->getData());
    }


    private function getData()
    {
        $data = [
            [
                // "type" => 'base',
                // "task_type" => 'purchase',
                // "quantity" => 100,
                // "price_single" => 70,
                // "price_old" => 70,


                // "name"=> "user",
                // "email" => "user@mail.com",
                // "balance" => 10000,
                // "password" => "$2y$10$4k1hi0aKPKAVzKSaI5ND3.oG4TfhtMwuxWBmhExRa0PstBgEn41xK",
                // "ip" => null,
                // "telegram_id" => 7895464,
                // "preferences" => json_encode('{"use_livecargo": false}'),
                // "role" => 0
            ],
           
        ];

        return $data;
    }
}
