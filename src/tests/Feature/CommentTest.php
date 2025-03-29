<?php

namespace Tests\Feature;

use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\CategoryItemTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function testコメント_コメント送信_ログイン済()
    {
        // 商品一覧データシーディング
        // $this->seed(ItemsTableSeeder::class);
        // $this->seed(CategoriesTableSeeder::class);
        // $this->seed(CategoryItemTableSeeder::class);

        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response =  $this->actingAs($user)->get('/item/:1');
        $response->assertStatus(200);


        $response = $this->actingAs($user)->post('/comment', [
            'comment' => 'テストコメント',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);
        // // ユーザーにログイン
        // $user = User::factory([
        //     'name' => 'testuser',
        //     'email' => 'abcd@example.com',
        //     'password' => 'password',
        // ])->create();

        // // ログイン
        // $this->actingAs($user);
        // // ログインできたかチェック
        // $this->assertAuthenticated();

        // // 商品一覧画面へ移動
        // $response = $this->get('/item/:1');
        // // 移動できたかテスト
        // $response->assertStatus(200);

        // // コメントを入力する
        // $user = Comment::factory([
        //     'user_id' => 1,
        //     'item_id' => 1,
        //     'comment' => 'AAAA',
        // ])->create();
        // // データベースにデータが存在するかをチェック
        // $this->assertDatabaseHas('comments', [
        //     'user_id' => 1,
        //     'item_id' => 1,
        //     'comment' => 'AAAA',
        // ]);

        // // コメントボタンを押す
        // $response = $this->post('/comment');
        // $response->assertStatus(302);

        // $response->assertSee('AAAA');
    }
}
