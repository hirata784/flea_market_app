<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;


class ProfileController extends Controller
{
    public function mypage(Request $request)
    {
        $data = $request->tab;
        $items = Item::all();
        $user = User::find(Auth::id());

        return view('mypage', compact('items', 'data', 'user'));
    }

    public function profile()
    {
        $user_all = User::all();
        $user_id = Auth::id();
        $user = $user_all[$user_id - 1];
        return view('mypage/profile', compact('user'));
    }

    public function email(Request $request)
    {
        $user_all = User::all();
        $user_id = Auth::id();
        $user = $user_all[$user_id - 1];
        $email_verified_at = date('Y-m-d H:i:s');
        // 注意：更新できない
        User::find($user_id)->update(['email_verified_at' => $email_verified_at]);
        return view('mypage/profile', compact('user'));
    }

    public function re_verified(Request $request)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        if (!$user['email_verified_at']) {
            // メール認証していなければ認証画面へ
            return view('verify-email');
        } else {
            // メール認証していれば商品一覧画面へ
            $items = Item::all();
            $user_id = Auth::id();
            $data = $request->tab;
            return view('index', compact('items', 'user_id', 'data'));
        }
    }

    public function address($item_buy, Request $request)
    {
        $item_detail = $item_buy;
        return view('/purchase/address', compact('item_detail'));
    }

    public function edit($item_detail, AddressRequest $request)
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
