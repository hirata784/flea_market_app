<?php

namespace Tests\Feature;

use App\Models\Comment;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\CategoryItemTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DetailTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test商品詳細情報取得_情報表示()
    {
        $this->seed(ItemsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(CategoryItemTableSeeder::class);
        $this->seed(UsersTableSeeder::class);

        // コメント送信
        Comment::factory([
            'user_id' => '1',
            'item_id' => '2',
            'comment' => 'aaaa',
        ])->create();
        Comment::factory([
            'user_id' => '1',
            'item_id' => '2',
            'comment' => 'bbbb',
        ])->create();

        // 商品詳細ページを開く
        $response = $this->get('/item/:2');
        $response->assertStatus(200);

        // 商品情報チェック
        $this->assertDatabaseHas('items', [
            'name' => 'HDD',
            'price' => '5000',
            'description' => '高速で信頼性の高いハードディスク',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            'condition' => '目立った傷や汚れなし',
            'brand' => 'ブランドB',
        ]);

        // いいね数チェック

        // カテゴリチェック
        $this->assertDatabaseHas('category_item', [
            'item_id' => '2',
            'category_id' => '3',
        ]);

        // コメントチェック
        $this->assertDatabaseHas('comments', [
            'user_id' => '1',
            'item_id' => '2',
            'comment' => 'aaaa',
        ]);
        $this->assertDatabaseHas('comments', [
            'user_id' => '1',
            'item_id' => '2',
            'comment' => 'bbbb',
        ]);

        // コメント数
        $recordCount = DB::table('comments')->count();
        $this->assertDatabaseCount('comments', $recordCount);
        // データベースのレコードが指定数より多いかどうか
        $this->assertTrue($recordCount >= 2);
    }

    public function test商品詳細情報取得_複数カテゴリ表示() {
        $this->seed(ItemsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(CategoryItemTableSeeder::class);
        $this->seed(UsersTableSeeder::class);

        // 商品詳細ページを開く
        $response = $this->get('/item/:1');
        $response->assertStatus(200);

        // 商品情報チェック
        $this->assertDatabaseHas('items', [
            'name' => '腕時計',
            'price' => '15000',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            'condition' => '良好',
            'brand' => 'ブランドA',
        ]);

        // カテゴリチェック
        $this->assertDatabaseHas('category_item', [
            'item_id' => '1',
            'category_id' => '1',
        ]);
        $this->assertDatabaseHas('category_item', [
            'item_id' => '1',
            'category_id' => '12',
        ]);
    }
}