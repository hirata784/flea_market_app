<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Payment;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use Ramsey\Uuid\Type\Integer;

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
        return view('item', compact('item_detail', 'comments'));
    }

    public function purchase($item_detail)
    {
        $payments = Payment::all();
        $item_buy = Item::find($item_detail);
        return view('purchase', compact('payments', 'item_buy'));
    }

    public function like($item_detail)
    {
        // Userのid取得
        $user_id = Auth::id();

        // Itemのid取得
        $item_id = (int)$item_detail;

        // 既にいいねしているかチェック
        $user = auth()->user();
        $isLiked = $user->items()->where('item_id', $item_id)->exists();
        // まだいいねしていない場合のみデータ追加
        if (!$isLiked) {
            $user = User::find($user_id);
            $user->items()->attach($item_id);
        }

        return redirect()->back();
    }

    public function unlike($item_detail)
    {
        // Userのid取得
        $user_id = Auth::id();

        // Itemのid取得
        $item_id = (int)$item_detail;

        // 既にいいねしているかチェック
        $user = auth()->user();
        $isLiked = $user->items()->where('item_id', $item_id)->exists();
        // 既にいいねしている場合のみデータ削除
        if ($isLiked) {
            // ここでいいね取消
            $user = User::find($user_id);
            $user->items()->detach($item_id);
        }

        return redirect()->back();
    }

    public function address()
    {
        return view('/purchase/address');
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
}
