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
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => bcrypt('testtest'),
            'nickname' => 'テスト太郎',
            'post_code' => '111-1111',
            'address' => 'テスト県テスト市テスト町',
            'building' => '1-2-34',
            'profile_img' => 'public/images/test_icon1.png',
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => 'テスト花子',
            'email' => 'hanako@example.com',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => bcrypt('hanakohanako'),
            'nickname' => 'テスト花子',
            'post_code' => '123-4567',
            'address' => 'メッセージ県メッセージ市メッセージ町',
            'profile_img' => 'public/images/test_icon2.jpg',
        ];
        DB::table('users')->insert($param);
    }
}
