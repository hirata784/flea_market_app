<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'method' => 'コンビニ払い'
        ];
        DB::table('payments')->insert($param);
        $param = [
            'method' => 'カード支払い'
        ];
        DB::table('payments')->insert($param);
    }
}