<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Sell;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\ProfileRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::all();
        $user_id = Auth::id();
        $data = $request->tab;
        return view('index', compact('items', 'user_id', 'data'));
    }

    public function search(Request $request)
    {
        $items = Item::KeywordSearch($request->keyword)->get();
        $data = $request->tab;
        return view('index', compact('items', 'data'));
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

    public function purchase($item_detail, Request $request)
    {
        $user = User::find(Auth::id());
        $item_buy = Item::find($item_detail);
        // 支払い方法 配列作成
        $payments = array(
            0 => 'コンビニ払い',
            1 => 'カード支払い',
        );

        // 届け先の初期値は登録済みの住所を代入
        $post_code = $user->post_code;
        $address = $user->address;
        $building = $user->building;
        return view('purchase', compact('user', 'item_buy', 'payments', 'post_code', 'address', 'building'));
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

    public function buy(PurchaseRequest $request)
    {
        // dd($request['payment']);
        // dd($request->all());
        $data = $request->tab;
        $items = Item::all();
        // Userのid取得
        $user_id = Auth::id();
        // Itemのid取得
        $item_id = $request['id'];
        // 送付先住所の取得
        $post_code = $request['post_code'];
        $address = $request['address'];
        $building = $request['building'];

        // itemテーブル
        Item::find($item_id)->update([
            'post_code' => $post_code,
            'address' => $address,
            'building' => $building,
        ]);

        // purchaseテーブル
        Purchase::create([
            'user_id' => $user_id,
            'item_id' => $item_id,
        ]);
        return view('index', compact('items', 'data'));
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
        return view('sell', compact('items', 'categories', 'product_conditions'));
    }

    public function edit(ProfileRequest $request)
    {
        $items = Item::all();
        $data = $request->tab;
        $form = $request->all();
        unset($form['_token']);
        User::find($request->id)->update($form);

        // 画像ファイルの保存場所指定
        if (request('profile_img')) {
            $filename = request()->file('profile_img')->getClientOriginalName();
            $inputs['profile_img'] = request('profile_img')->storeAs('public/images', $filename);
            User::find($request->id)->update($inputs);
        }
        return view('index', compact('items', 'data'));
    }
}
