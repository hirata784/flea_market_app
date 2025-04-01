<?php

namespace Tests\Feature;

use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    public function test商品検索機能_部分一致検索()
    {
        // 商品一覧データシーディング
        $this->seed(ItemsTableSeeder::class);
        // 商品一覧画面へ移動
        $response = $this->get('/');
        // 移動できたかテスト
        $response->assertStatus(200);
        // 全商品表示できたかテスト
        $response->assertSee('腕時計');
        $response->assertSee('HDD');
        $response->assertSee('玉ねぎ3束');
        $response->assertSee('革靴');
        $response->assertSee('ノートPC');
        $response->assertSee('マイク');
        $response->assertSee('ショルダーバッグ');
        $response->assertSee('タンブラー');
        $response->assertSee('コーヒーミル');
        $response->assertSee('メイクセット');

        // キーワードを入力
        $response = $this->get('/search?_token=NVrsOhfiJAjGKsCzRzMA4NrTFSOmfbhjS63i73cd&keyword=マ');

        // 商品一覧画面に「マイク」のみ表示
        $response->assertSee('マイク');
        $response->assertDontSee('ショルダーバッグ');
    }
}
