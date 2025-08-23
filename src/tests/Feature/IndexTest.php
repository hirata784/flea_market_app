<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\Sell;
use App\Models\User;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    // 4.商品一覧取得
    public function test商品一覧取得_全商品取得()
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
    }

    public function test商品一覧取得_購入済み商品()
    {
        // 商品一覧データシーディング
        $this->seed(ItemsTableSeeder::class);

        //userFactory作成
        $this->user = User::factory()->create();
        //itemFactory作成
        $this->item = Item::factory()->create();
        // 余分なデータを削除
        $this->item->delete(11);

        // 商品一覧画面へ移動
        $response = $this->get('/');
        // 移動できたかテスト
        $response->assertStatus(200);
        // 購入前はSold非表示
        $response->assertDontSee('Sold');

        // 商品購入者データ作成
        $this->purchase = Purchase::factory()->create([
            'user_id' => '1',
            'item_id' => '1',
        ]);
        // 作成できたかチェック
        $this->assertDatabaseHas('purchases', [
            'user_id' => '1',
            'item_id' => '1',
        ]);

        // 購入後、再び商品一覧画面を開く
        $response = $this->get('/');
        // 移動できたかテスト
        $response->assertStatus(200);

        // 購入後はSold表示
        $response->assertSee('Sold');
    }

    public function test商品一覧取得_自分の出品商品_表示しない()
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
        // 余分なデータを削除
        $this->item->delete(12);

        // 商品出品者データ作成
        $this->sell = Sell::factory()->create([
            'user_id' => '1',
            'item_id' => '11',
        ]);
        // 作成できたかチェック
        $this->assertDatabaseHas('sells', [
            'user_id' => '1',
            'item_id' => '11',
        ]);

        // 商品一覧画面へ移動
        $response = $this->get('/');
        // 移動できたかテスト
        $response->assertStatus(200);
        // 出品者以外のページでは表示する
        $response->assertSee('AAAA');

        // 出品者のユーザーにログイン
        $response =  $this->actingAs($this->user)->get('/');
        // 移動できたかテスト
        $response->assertStatus(200);
        // 出品者が出品した商品は表示しない
        $response->assertDontSee('AAAA');
    }
}
