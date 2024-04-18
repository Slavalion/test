<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarrifsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tarrifs')->insert($this->getData());
    }


    private function getData()
    {
        $data = [
            [
                "type" => 'base',
                "task_type" => 'purchase',
                "quantity" => 100,
                "price_single" => 70,
                "price_old" => 70,
            ],
            [
                "type" => 'advanced',
                "task_type" => 'purchase',
                "quantity" => 200,
                "price_single" => 65,
                "price_old" => 70,
            ],
            [
                "type" => 'pac300',
                "task_type" => 'purchase',
                "quantity" => 300,
                "price_single" => 60,
                "price_old" => 60,
            ],
            [
                "type" => 'pac500',
                "task_type" => 'purchase',
                "quantity" => 500,
                "price_single" => 55,
                "price_old" => 55,
            ],
            [
                "type" => 'profi',
                "task_type" => 'purchase',
                "quantity" => 1000,
                "price_single" => 50,
                "price_old" => 50,
            ],
            [
                "type" => 'smart',
                "task_type" => 'purchase',
                "quantity" => 3000,
                "price_single" => 40,
                "price_old" => 45,
            ],
            [
                "type" => 'base',
                "task_type" => 'review',
                "quantity" => 100,
                "price_single" => 70,
                "price_old" => 70,
            ],
            [
                "type" => 'advanced',
                "task_type" => 'review',
                "quantity" => 200,
                "price_single" => 65,
                "price_old" => 70,
            ],
            [
                "type" => 'pac300',
                "task_type" => 'review',
                "quantity" => 300,
                "price_single" => 60,
                "price_old" => 60,
            ],
            [
                "type" => 'pac500',
                "task_type" => 'review',
                "quantity" => 500,
                "price_single" => 55,
                "price_old" => 55,
            ],
            [
                "type" => 'profi',
                "task_type" => 'review',
                "quantity" => 1000,
                "price_single" => 50,
                "price_old" => 50,
            ],
            [
                "type" => 'smart',
                "task_type" => 'review',
                "quantity" => 3000,
                "price_single" => 40,
                "price_old" => 45,
            ],
             [
                "type" => 'base',
                "task_type" => 'question',
                "quantity" => 100,
                "price_single" => 70,
                "price_old" => 70,
            ],
            [
                "type" => 'advanced',
                "task_type" => 'question',
                "quantity" => 200,
                "price_single" => 65,
                "price_old" => 70,
            ],
            [
                "type" => 'pac300',
                "task_type" => 'question',
                "quantity" => 300,
                "price_single" => 60,
                "price_old" => 60,
            ],
            [
                "type" => 'pac500',
                "task_type" => 'question',
                "quantity" => 500,
                "price_single" => 55,
                "price_old" => 55,
            ],
            [
                "type" => 'profi',
                "task_type" => 'question',
                "quantity" => 1000,
                "price_single" => 50,
                "price_old" => 50,
            ],
            [
                "type" => 'smart',
                "task_type" => 'question',
                "quantity" => 3000,
                "price_single" => 40,
                "price_old" => 45,
            ]
        ];

        return $data;
    }
}
