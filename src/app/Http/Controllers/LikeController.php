<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
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
}
