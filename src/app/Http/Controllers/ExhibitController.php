<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Sell;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;

class ExhibitController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        // 商品の状態値 配列作成
        $product_conditions = array(
            0 => '良好',
            1 => '目立った傷や汚れなし',
            2 => 'やや傷や汚れあり',
            3 => '状態が悪い',
        );
        return view('exhibit', compact('categories', 'product_conditions'));
    }

    public function add(ExhibitionRequest $request)
    {
        $items = Item::all();
        $categories = Category::all();
        // 商品の状態値 配列作成
        $product_conditions = array(
            0 => '良好',
            1 => '目立った傷や汚れなし',
            2 => 'やや傷や汚れあり',
            3 => '状態が悪い',
        );

        // itemsテーブル
        $item_detail = $request->only('img_url', 'condition', 'name', 'brand', 'description', 'price');
        Item::create($item_detail);

        // 画像ファイルの保存場所指定
        if (request('img_url')) {
            $filename = request()->file('img_url')->getClientOriginalName();
            $inputs['img_url'] = request('img_url')->storeAs('public/images', $filename);
            Item::find(Item::count())->update($inputs);
        }

        // category_itemテーブル
        $item_id = item::count();
        Item::find($item_id)->categories()->attach($request->category);

        // sellsテーブル
        // Userのid取得
        $user_id = Auth::id();

        Sell::create([
            'user_id' => $user_id,
            'item_id' => $item_id,
        ]);
        return redirect()->action([ListController::class, 'index']);
    }
}
