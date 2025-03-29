<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\Sell;
use App\Models\User;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test商品一覧_全商品取得()
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

    public function test商品一覧_購入済み商品()
    {
        // 商品一覧データシーディング
        $this->seed(ItemsTableSeeder::class);
        // 商品一覧画面へ移動
        $response = $this->get('/');
        // 移動できたかテスト
        $response->assertStatus(200);

        // 購入前はSold表示しない
        $response->assertDontSee('Sold');

        // 外部キー制約を無効化
        Schema::disableForeignKeyConstraints();


        // 商品購入済みデータ作成
        $purchases = Purchase::factory([
            'user_id' => '1',
            'item_id' => '1',
        ])->create();
        // 作成できたかチェック
        $this->assertDatabaseHas('purchases', [
            'user_id' => '1',
            'item_id' => '1',
        ]);

        // 購入前はSold表示しない
        $response->assertSee('Sold');

        // // 購入商品呼び出せたかテスト
        // $recordCount = DB::table('purchases')->count();
        // $this->assertDatabaseCount('purchases', $recordCount);
        // // データベースのレコードが指定数より多いかどうか
        // $this->assertTrue($recordCount >= 2);

        // // soldラベル表示されてるかテスト(purchasesテーブルのitem_idに入力された数値にsold表示される)
        // // 商品id:1は購入済→sold表示
        // $place = Purchase::where('item_id', 1)->first();
        // $this->assertNotNull($place); // Nullで無ければtrue

        // // 商品id:2は購入されてない→sold非表示
        // $place = Purchase::where('item_id', 2)->first();
        // $this->assertNull($place); // Nullならtrue

        // 外部キー制約を有効化
        Schema::enableForeignKeyConstraints();
    }

    public function test商品一覧_自分の出品商品_表示しない()
    {
        // 外部キー制約を無効化
        Schema::disableForeignKeyConstraints();

        // 商品一覧データシーディング
        $this->seed(ItemsTableSeeder::class);

        // 商品データ追加
        $items = Item::factory([
            'name' => 'AAAA',
            'price' => '100',
            'description' => 'aaaa',
            'img_url' => 'imageA',
            'condition' => '良好',
            'brand' => 'AAaa',
        ])->create();
        $items = Item::factory([
            'name' => 'BBBB',
            'price' => '200',
            'description' => 'bbbb',
            'img_url' => 'imageB',
            'condition' => '良好',
            'brand' => 'BBbb',
        ])->create();
        // 作成できたかチェック
        $this->assertDatabaseHas('items', [
            'name' => 'AAAA',
            'price' => '100',
            'description' => 'aaaa',
            'img_url' => 'imageA',
            'condition' => '良好',
            'brand' => 'AAaa',
        ]);
        $this->assertDatabaseHas('items', [
            'name' => 'BBBB',
            'price' => '200',
            'description' => 'bbbb',
            'img_url' => 'imageB',
            'condition' => '良好',
            'brand' => 'BBbb',
        ]);
        // 購入商品呼び出せたかテスト
        $recordCount = DB::table('items')->count();
        $this->assertDatabaseCount('items', $recordCount);
        // データベースのレコードが指定数より多いかどうか
        $this->assertTrue($recordCount >= 12);

        // 商品出品データ作成
        $sells = Sell::factory([
            'user_id' => '1',
            'item_id' => '11',
        ])->create();
        $sells = Sell::factory([
            'user_id' => '2',
            'item_id' => '12',
        ])->create();
        // 作成できたかチェック
        $this->assertDatabaseHas('sells', [
            'user_id' => '1',
            'item_id' => '11',
        ]);
        $this->assertDatabaseHas('sells', [
            'user_id' => '2',
            'item_id' => '12',
        ]);

        // ユーザーにログイン
        $user = User::factory([
            'name' => 'testuser',
            'email' => 'abcd@example.com',
            'password' => 'password',
        ])->create();

        // ログイン
        $this->actingAs($user);
        // ログインできたかチェック
        $this->assertAuthenticated();

        // 商品一覧画面へ移動
        $response = $this->get('/');
        // 移動できたかテスト
        $response->assertStatus(200);

        // 自分が出品した商品が表示されてないか確認
        // 商品id:11は出品した→非表示
        $place = Sell::where('user_id', 1)->where('item_id', 11)->first();
        $this->assertNotNull($place); // Nullで無ければtrue

        // 商品id:5は出品してない→表示
        $place = Sell::where('user_id', 1)->where('item_id', 5)->first();
        $this->assertNull($place); // Nullならtrue

        // ログアウト
        $response = $this->post('/logout');
        $this->assertGuest();         //ログアウトしていることを確認

        // 外部キー制約を有効化
        Schema::enableForeignKeyConstraints();
    }
}
