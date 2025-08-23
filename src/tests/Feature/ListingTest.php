<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\CategoryItemTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Tests\TestCase;

class ListingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // 15.出品商品情報取得
    public function test出品商品情報登録_必須情報保存()
    {
        // 商品一覧データシーディング
        $this->seed(ItemsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(CategoryItemTableSeeder::class);

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
        //categoryFactory作成
        $this->items = Category::factory()->create();
        // 余分なデータを削除
        $this->item->delete(12);

        // 商品カテゴリーデータ作成
        $this->categories = Category::factory()->items->create([
            'item_id' => '11',
            'category_id' => '1',
        ]);
        // 作成できたかチェック
        $this->assertDatabaseHas('category_item', [
            'item_id' => '11',
            'category_id' => '1',
        ]);
    }
}
