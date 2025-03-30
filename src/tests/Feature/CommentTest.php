<?php

namespace Tests\Feature;

use Database\Seeders\UsersTableSeeder;
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
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function testコメント送信機能_ログイン済コメント送信()
    {
        $this->item = $this->seed(ItemsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(CategoryItemTableSeeder::class);

        // ●ユーザーにログイン
        $user = User::factory()->create();
        $response =  $this->actingAs($user)->get('/item/:1');

        // ログインできてることをテスト
        $response->assertStatus(200);

        $postData = [
            'user_id' => $this->$user->id,
            'item_id' => $this->item->id,
            'comment' => 'AAAAA'
        ];

        // リクエストの送信
        $response = $this->actingAs($user)->post(route('comment'), $postData);

        // ●コメントを入力
        // $response = $this->post('/comment', [
        //     'user_id' => $this->user->id,
        //     'item_id' => $this->item->id,
        //     'comment' => 'AAAAA'
        // ]);

        // $response->assertStatus(302);

        // ●コメントボタンを押す
        // データベースにコメント入っているかテスト

        $this->assertDatabaseHas('comments', [
            'user_id' => '1',
            'item_id' => '1',
            'comment' => 'BBBBB'
        ]);
    }





    // // データは入った
    // public function setUp(): void
    // {
    //     parent::setUp();
    //     //userFactory作成
    //     $this->user = User::factory()->create();
    //     //itemFactory作成
    //     $this->item = Item::factory()->create();

    //     $this->comment = Comment::factory()->create([
    //         'user_id' => $this->user->id,
    //         'item_id' => $this->item->id,
    //         'comment' => 'AAAAA'
    //     ]);
    // }

    // public function testコメント_コメント送信_ログイン済()
    // {
    //     $this->assertDatabaseHas('comments', [
    //         'user_id' => '1',
    //         'item_id' => '1',
    //         'comment' => 'BBBBB'
    //     ]);
    // }
}
