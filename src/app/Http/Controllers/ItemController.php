<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        $user_id = Auth::id();
        return view('index', compact('items', 'user_id'));
    }

    public function search(Request $request)
    {
        $items = Item::KeywordSearch($request->keyword)->get();
        return view('index', compact('items'));
    }

    public function item($item_id, Request $request)
    {
        $item_detail = Item::find($item_id);
        // 閲覧中の商品コメントのみ表示
        $comments = Comment::CommentSearch($request->item_id)->get();

        // 閲覧中のカテゴリーのみ表示
        $categories = $item_detail->categories;
        return view('item', compact('item_detail', 'comments', 'categories'));
    }

    public function purchase($item_detail)
    {
        $payments = Payment::all();
        $item_buy = Item::find($item_detail);
        return view('purchase', compact('payments', 'item_buy'));
    }

    public function comment(CommentRequest $request)
    {
        // Userのid取得
        $user_id = Auth::id();
        // Itemのid取得
        $item_id = (int)$request['id'];
        // コメント取得
        $comment = $request['comment'];

        Comment::create([
            'user_id' => $user_id,
            'item_id' => $item_id,
            'comment' => $comment,
        ]);
        return back();
    }

    public function tab(Request $request)
    {
        if (Auth::check()) {
            // 認証時、いいねしている商品を取得
            // Userのid取得
            $user_id = Auth::id();
            // ユーザーがいいねした商品のみ出力
            $items = User::find($user_id)->items;
        } else {
            // 未認証時、全商品を仮代入
            $items = Item::all();
        }
        return view('index', compact('items'));
    }

    public function sell()
    {
        $categories = Category::all();
        // 商品の状態値 配列作成
        $product_conditions = array(
            0 => '良好',
            1 => '目立った傷や汚れなし',
            2 => 'やや傷や汚れあり',
            3 => '状態が悪い',
        );
        return view('sell', compact('categories', 'product_conditions'));
    }

    public function buy(Request $request)
    {
        $items = Item::all();
        // Userのid取得
        $user_id = Auth::id();
        // Itemのid取得
        $item_id = $request['id'];

        Purchase::create([
            'user_id' => $user_id,
            'item_id' => $item_id,
        ]);
        return view('index', compact('items'));
    }

    public function add(Request $request)
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
        // 画像は一旦仮で代入
        $item_detail = $request->only('img_url', 'condition', 'name', 'brand', 'description', 'price');
        Item::create($item_detail);

        // category_itemテーブル
        $item_id = item::count();
        Item::find($item_id)->categories()->attach($request->category);
        return view('sell', compact('items', 'categories', 'product_conditions'));
    }
}
