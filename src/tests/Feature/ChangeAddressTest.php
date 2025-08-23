<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ChangeAddressTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    // 12.配送先変更機能
    public function test配送先変更機能_登録住所反映()
    {
        // 商品一覧データシーディング
        $this->seed(ItemsTableSeeder::class);

        //userFactory作成
        $this->user = User::factory()->create();
        //itemFactory作成
        $this->item = Item::factory()->create();

        // データベースにデータが存在するかをチェック
        $this->assertDatabaseHas('users', [
            'post_code' => $this->user['post_code'],
            'address' => $this->user['address'],
            'building' => $this->user['building'],
        ]);

        // ユーザーにログイン後、購入画面を開く
        $response =  $this->actingAs($this->user)->get('/purchase/:1');
        $response->assertStatus(200);

        // 送り先住所の変更前、プロフィール登録住所が表示
        $response->assertSee($this->user['post_code']);
        $response->assertSee($this->user['address']);
        $response->assertSee($this->user['building']);

        // 住所変更画面へ移動
        $response = $this->get('purchase/address/:1');
        $response->assertStatus(200);

        // 住所変更画面で住所を登録し、再度購入画面へ移動
        $response = $this->post('/purchase/address/:1/update', [
            'post_code' => "123-4567",
            'address' => "テスト県テスト市",
            'building' => "テスト区98-7-6",
        ]);
        $response->assertStatus(200);

        // 送り先住所が変更画面で登録した住所であることを確認
        $response->assertSee("123-4567");
        $response->assertSee("テスト県テスト市");
        $response->assertSee("テスト区98-7-6");
    }

    public function test配送先変更機能_購入商品に送り先住所登録()
    {
        // 商品一覧データシーディング
        $this->seed(ItemsTableSeeder::class);

        //userFactory作成
        $this->user = User::factory()->create();
        //itemFactory作成
        $this->item = Item::factory()->create();

        // データベースにデータが存在するかをチェック
        $this->assertDatabaseHas('users', [
            'post_code' => $this->user['post_code'],
            'address' => $this->user['address'],
            'building' => $this->user['building'],
        ]);

        // ユーザーにログイン後、購入画面を開く
        $response =  $this->actingAs($this->user)->get('/purchase/:1');
        $response->assertStatus(200);

        // 送り先住所の変更前、プロフィール登録住所が表示
        $response->assertSee($this->user['post_code']);
        $response->assertSee($this->user['address']);
        $response->assertSee($this->user['building']);

        // 住所変更画面へ移動
        $response = $this->get('/purchase/address/:1');
        $response->assertStatus(200);

        // 住所変更画面で住所を登録し、再度購入画面へ移動
        $response = $this->post('/purchase/address/:1/update', [
            'post_code' => "123-4567",
            'address' => "テスト県テスト市",
            'building' => "テスト区98-7-6",
        ]);
        $response->assertStatus(200);

        // 送り先住所が変更画面で登録した住所であることを確認
        $response->assertSee("123-4567");
        $response->assertSee("テスト県テスト市");
        $response->assertSee("テスト区98-7-6");

        // 商品を購入する
        $response = $this->post('/', [
            'post_code' => "123-4567",
            'address' => "テスト県テスト市",
            'building' => "テスト区98-7-6",
        ]);
        $response->assertStatus(200);

        // 購入商品に住所が紐づいてるか確認
        $this->assertDatabaseHas('items', [
            'name' => '腕時計',
            'price' => '15000',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            'condition' => '良好',
            'brand' => 'ブランドA',
            'post_code' => "123-4567",
            'address' => "テスト県テスト市",
            'building' => "テスト区98-7-6",
        ]);
    }
}
