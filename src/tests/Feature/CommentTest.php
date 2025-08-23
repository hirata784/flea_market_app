<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\CategoryItemTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // 9.コメント送信機能
    public function testコメント送信機能_ログイン済コメント送信()
    {
        $this->item = $this->seed(ItemsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(CategoryItemTableSeeder::class);

        // ユーザーにログイン
        $user = User::factory()->create();
        $response =  $this->actingAs($user)->get('/item/:1');

        //userFactory作成
        $this->user = User::factory()->create();
        //itemFactory作成
        $this->item = Item::factory()->create();

        // ログインできてることをテスト
        $response->assertStatus(200);

        // コメントデータ作成
        $this->items = Comment::factory()->create([
            'user_id' => '1',
            'item_id' => '1',
        ]);
        // 作成できたかチェック
        $this->assertDatabaseHas('comments', [
            'user_id' => '1',
            'item_id' => '1',
        ]);

        // コメントを入力
        $response = $this->post('/comment', [
            'user_id' => '1',
            'item_id' => '1',
            'comment' => 'AAAAA'
        ]);

        // コメントボタンを押す
        // データベースにコメント入っているかテスト
        $this->assertDatabaseHas('comments', [
            'user_id' => '1',
            'item_id' => '1',
            'comment' => 'AAAAA'
        ]);
    }
}
