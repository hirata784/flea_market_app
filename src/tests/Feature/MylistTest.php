<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MylistTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    public function testマイリスト一覧取得_いいね商品取得()
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

        //userFactory作成
        $this->user = User::factory()->create();
        //itemFactory作成
        $this->item = Item::factory()->create();
        // 余分なデータを削除
        $this->item->delete(12);

        // いいねデータ作成
        $this->like = Like::factory()->create([
            'user_id' => '1',
            'item_id' => '1',
        ]);
        // 作成できたかチェック
        $this->assertDatabaseHas('item_user', [
            'user_id' => '1',
            'item_id' => '1',
        ]);

        // 出品者のユーザーにログイン
        $response =  $this->actingAs($this->user)->get('/');
        // 移動できたかテスト
        $response->assertStatus(200);
    }
}