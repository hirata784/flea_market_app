<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function index($item_detail, Request $request)
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

    public function update(PurchaseRequest $request)
    {
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
        return redirect()->action([ListController::class, 'index']);
    }
}
