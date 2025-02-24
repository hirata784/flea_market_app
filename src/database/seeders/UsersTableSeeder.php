<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'テスト太郎',
            'email' => 'taro@example.com',
            'password' => bcrypt('testtest'),
            'post_code' => '111-1111',
            'address' => 'テスト県テスト市テスト町',
            'building' => '1-2-34',
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => 'テスト花子',
            'email' => 'hanako@example.com',
            'password' => bcrypt('hanakohanako'),
            'post_code' => '123-4567',
            'address' => 'メッセージ県メッセージ市メッセージ町',
        ];
        DB::table('users')->insert($param);
    }
}
