<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function mypage(Request $request)
    {
        $data = $request->tab;
        $items = Item::all();
        return view('mypage', compact('items', 'data'));
    }

    public function profile()
    {
        $user_all = User::all();
        $user_id = Auth::id();
        $user = $user_all[$user_id - 1];

        return view('mypage/profile', compact('user'));
    }

    public function address($item_buy, Request $request)
    {
        $item_detail = $item_buy;
        // 入力済みの送付先住所の取得
        $old_post_code = $request['post_code'];
        $old_address = $request['address'];
        $old_building = $request['building'];
        return view('/purchase/address', compact('item_detail', 'old_post_code', 'old_address' , 'old_building'));
    }

    public function edit($item_detail, Request $request)
    {
        $user = User::find(Auth::id());
        $item_buy = Item::find($item_detail);
        // 支払い方法 配列作成
        $payments = array(
            0 => 'コンビニ払い',
            1 => 'カード支払い',
        );

        if ($request['post_code'] == "") {
            // 空白の場合、住所変更なし
            $post_code = $request->old_post_code;
        } else {
            // 住所の変更をした場合、届け先を更新
            $post_code = $request->post_code;
        }

        if ($request['address'] == "") {
            $address = $request->old_address;
        } else {
            $address = $request->address;
        }

        if ($request['building'] == "") {
            // 空白の場合、空白へ(建物はnull許可してる為)
            $building = "";
        } else {
            $building = $request->building;
        }

        return view('purchase', compact('user', 'item_buy', 'payments', 'post_code', 'address', 'building'));
    }
}
