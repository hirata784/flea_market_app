<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '腕時計',
            'price' => '15000',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            'condition' => '良好',
            'brand' => 'ブランドA',
        ];
        DB::table('items')->insert($param);
        $param = [
            'name' => 'HDD',
            'price' => '5000',
            'description' => '高速で信頼性の高いハードディスク',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            'condition' => '目立った傷や汚れなし',
            'brand' => 'ブランドB',
        ];
        DB::table('items')->insert($param);
        $param = [
            'name' => '玉ねぎ3束',
            'price' => '300',
            'description' => '新鮮な玉ねぎ3束のセット',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            'condition' => 'やや傷や汚れあり',
            'brand' => '',
        ];
        DB::table('items')->insert($param);
        $param = [
            'name' => '革靴',
            'price' => '4000',
            'description' => 'クラシックなデザインの革靴',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            'condition' => '状態が悪い',
            'brand' => 'ブランドD',
        ];
        DB::table('items')->insert($param);
        $param = [
            'name' => 'ノートPC',
            'price' => '45000',
            'description' => '高性能なノートパソコン',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            'condition' => '良好',
            'brand' => 'ブランドE',
        ];
        DB::table('items')->insert($param);
        $param = [
            'name' => 'マイク',
            'price' => '8000',
            'description' => '高音質のレコーディング用マイク',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            'condition' => '目立った傷や汚れなし',
            'brand' => 'ブランドF',
        ];
        DB::table('items')->insert($param);
        $param = [
            'name' => 'ショルダーバッグ',
            'price' => '3500',
            'description' => 'おしゃれなショルダーバッグ',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            'condition' => 'やや傷や汚れあり',
            'brand' => 'ブランドG',
        ];
        DB::table('items')->insert($param);
        $param = [
            'name' => 'タンブラー',
            'price' => '500',
            'description' => '使いやすいタンブラー',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            'condition' => '状態が悪い',
            'brand' => 'ブランドH',
        ];
        DB::table('items')->insert($param);
        $param = [
            'name' => 'コーヒーミル',
            'price' => '4000',
            'description' => '手動のコーヒーミル',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            'condition' => '良好',
            'brand' => 'ブランドI',
        ];
        DB::table('items')->insert($param);
        $param = [
            'name' => 'メイクセット',
            'price' => '2500',
            'description' => '便利なメイクアップセット',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
            'condition' => '目立った傷や汚れなし',
            'brand' => 'ブランドJ',
        ];
        DB::table('items')->insert($param);
    }
}
