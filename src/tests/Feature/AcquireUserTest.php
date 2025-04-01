<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\Sell;
use App\Models\User;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcquireUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function testユーザー情報取得_必須情報取得()
    {
        // 商品一覧データシーディング
        $this->seed(ItemsTableSeeder::class);
        // 出品用データ追加
        $items = Item::factory([
            'name' => 'AAAA',
            'price' => '100',
            'description' => 'aaaa',
            'img_url' => 'imageA.jpg',
            'condition' => '良好',
            'brand' => 'AAaa',
        ])->create();
        // 作成できたかチェック
        $this->assertDatabaseHas('items', [
            'name' => 'AAAA',
            'price' => '100',
            'description' => 'aaaa',
            'img_url' => 'imageA.jpg',
            'condition' => '良好',
            'brand' => 'AAaa',
        ]);

        //userFactory作成
        $this->user = User::factory()->create();
        //itemFactory作成
        $this->item = Item::factory()->create();

        // データベースにデータが存在するかをチェック
        // (プロフィール画像、ユーザー名）
        $this->assertDatabaseHas('users', [
            'profile_img' =>  $this->user['profile_img'],
            'nickname' =>  $this->user['nickname'],
        ]);

        // 購入者データ作成
        $this->purchase = Purchase::factory()->create([
            'user_id' => '1',
            'item_id' => '1',
        ]);
        // 作成できたかチェック
        $this->assertDatabaseHas('purchases', [
            'user_id' => '1',
            'item_id' => '1',
        ]);

        // 出品者データ作成
        $this->sell = Sell::factory()->create([
            'user_id' => '1',
            'item_id' => '11',
        ]);
        // 作成できたかチェック
        $this->assertDatabaseHas('sells', [
            'user_id' => '1',
            'item_id' => '11',
        ]);

        // ユーザーにログイン後、プロフィール画面を開く
        $response =  $this->actingAs($this->user)->post('/mypage');
        $response->assertStatus(200);

        // 画面上に各項目が表示されているかテスト
        $response->assertSee($this->user['profile_img']);
        $response->assertSee($this->user['nickname']);

        // 購入商品リストへ移動
        $response = $this->post('/mypage?tab=buy');
        $response->assertSee('腕時計');

        // 出品商品リストへ移動
        $response = $this->post('/mypage?tab=sell');
        $response->assertSee('AAAA');
    }
}
