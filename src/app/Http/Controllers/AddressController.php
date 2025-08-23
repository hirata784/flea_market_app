<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;


class AddressController extends Controller
{
    public function index($item_buy, Request $request)
    {
        $item_detail = $item_buy;
        return view('/purchase/address', compact('item_detail'));
    }

    public function update($item_detail, AddressRequest $request)
    {
        $user = User::find(Auth::id());
        $item_buy = Item::find($item_detail);
        // 支払い方法 配列作成
        $payments = array(
            0 => 'コンビニ払い',
            1 => 'カード支払い',
        );

        // 住所の変更
        $post_code = $request->post_code;
        $address = $request->address;
        $building = $request->building;
        return view('purchase', compact('user', 'item_buy', 'payments', 'post_code', 'address', 'building'));
    }
}
