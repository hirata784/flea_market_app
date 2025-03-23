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

class MylistTest extends TestCase
{
    use RefreshDatabase;

    public function testマイリスト_いいね商品取得()
    {
        // 商品一覧データシーディング
        $this->seed(ItemsTableSeeder::class);

        // いいねデータ作成
        $purchases = User::factory([
            'user_id' => User::find(1),
            'item_id' => User::items()->attach(1),
        ])->create();
        $purchases = User::factory([
            'user_id' => '2',
            'item_id' => '3',
        ])->create();

        // 作成できたかチェック
        $this->assertDatabaseHas('item_user', [
            'user_id' => '1',
            'item_id' => '1',
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


        // マイリストへ移動
        $response = $this->get('/?tab=mylist');
        // 移動できたかテスト
        $response->assertStatus(200);

        // 全商品呼び出したかテスト
        $recordCount = DB::table('items')->count();
        $this->assertDatabaseCount('items', $recordCount);
        // データベースのレコードが指定数より多いかどうか
        $this->assertTrue($recordCount >= 10);
    }


    public function testマイリスト_購入済み商品()
    {
        // 外部キー制約を無効化
        Schema::disableForeignKeyConstraints();

        // 商品一覧データシーディング
        $this->seed(ItemsTableSeeder::class);

        // 商品購入済みデータ作成
        $purchases = Purchase::factory([
            'user_id' => '1',
            'item_id' => '1',
        ])->create();
        $purchases = Purchase::factory([
            'user_id' => '2',
            'item_id' => '3',
        ])->create();
        // 作成できたかチェック
        $this->assertDatabaseHas('purchases', [
            'user_id' => '1',
            'item_id' => '1',
        ]);
        $this->assertDatabaseHas('purchases', [
            'user_id' => '2',
            'item_id' => '3',
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

        // マイリストへ移動
        $response = $this->get('/?tab=mylist');
        // 移動できたかテスト
        $response->assertStatus(200);

        // 購入商品呼び出せたかテスト
        $recordCount = DB::table('purchases')->count();
        $this->assertDatabaseCount('purchases', $recordCount);
        // データベースのレコードが指定数より多いかどうか
        $this->assertTrue($recordCount >= 2);

        // soldラベル表示されてるかテスト(purchasesテーブルのitem_idに入力された数値にsold表示される)
        // 商品id:1は購入済→sold表示
        $place = Purchase::where('item_id', 1)->first();
        $this->assertNotNull($place); // Nullで無ければtrue

        // 商品id:2は購入されてない→sold非表示
        $place = Purchase::where('item_id', 2)->first();
        $this->assertNull($place); // Nullならtrue

        // 外部キー制約を有効化
        Schema::enableForeignKeyConstraints();
    }

    public function testマイリスト_自分の出品商品_表示しない()
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

        // マイリストへ移動
        $response = $this->get('/?tab=mylist');
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

    public function testマイリスト_未認証非表示()
    {
        // ログアウトしていることを確認
        $this->assertGuest();
        // マイリストへ移動
        $response = $this->get('/?tab=mylist');
        // 移動できたかテスト
        $response->assertStatus(200);
        // 何も表示されないことをテスト
    }
}
