<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;

class DetailController extends Controller
{
    public function index($item_id, Request $request)
    {
        $item_detail = Item::find($item_id);
        // 閲覧中の商品コメントのみ表示
        $comments = Comment::CommentSearch($request->item_id)->get();

        // 閲覧中のカテゴリーのみ表示
        $categories = $item_detail->categories;
        return view('detail', compact('item_detail', 'comments', 'categories'));
    }

    public function addLike($item_detail)
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

    public function deleteLike($item_detail)
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

    public function addComment(CommentRequest $request)
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
}
