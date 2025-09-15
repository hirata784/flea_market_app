<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Sell;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index($item_id, Request $request)
    {
        $user_id = User::find(Auth::id())->id;
        $purchases = Purchase::all();
        $sells = Sell::all();
        $items = Item::all();
        $item_detail = Item::find($item_id);

        // 自分が購入者か出品者か調べる
        $Seller = $sells->where('item_id', $item_id)->where('user_id', $user_id)->first();
        $purchaser = $purchases->where('item_id', $item_id)->where('user_id', $user_id)->first();

        if (isset($purchaser)) {
            // 自分が購入者の場合、出品者のデータを取得する
            $sell = $sells->where('item_id', $item_id)->first();
            $user = User::find($sell->user_id);
        } elseif (isset($Seller)) {
            // 自分が出品者の場合、購入者のデータを取得する
            $purchase = $purchases->where('item_id', $item_id)->first();
            $user = User::find($purchase->user_id);
        }

        return view('transaction', compact('item_detail', 'user', 'items'));
    }
}
