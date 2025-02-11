<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('index', compact('items'));
    }

    public function search(Request $request)
    {
        $items = Item::KeywordSearch($request->keyword)->get();
        return view('index', compact('items'));
    }

    public function item($item_id)
    {
        $item_detail = Item::find($item_id);
        return view('item', compact('item_detail'));
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
        $existingLike = like::where('item_id', $item_id)
            ->where('user_id', $user_id)
            ->first();

        // いいねがされていない場合、新しいいいね作成
        if (!$existingLike) {
            Like::create([
                'user_id' => $user_id,
                'item_id' => $item_id,
            ]);
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
        $existingLike = like::where('item_id', $item_id)
            ->where('user_id', $user_id)
            ->first();

        // いいねがされている場合、削除
        if ($existingLike) {
            Like::find($existingLike->id)->delete();
        }
        return redirect()->back();
    }

    public function address(){
        return view('/purchase/address');
    }
}
