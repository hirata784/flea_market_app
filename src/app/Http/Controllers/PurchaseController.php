<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

use Stripe\Stripe;
use Stripe\PaymentIntent;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index($item_detail, Request $request)
    {
        $user = User::find(Auth::id());
        $item_buy = Item::find($item_detail);

        // Stripe の秘密鍵を設定
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // PaymentIntent を作成
        $paymentIntent = PaymentIntent::create([
            'amount' => $item_buy->price,   // 商品価格 (円なので整数)
            'currency' => 'jpy',
            'metadata' => [
                'user_id' => $user->id,
                'item_id' => $item_buy->id,
            ],
        ]);
        $client_secret = $paymentIntent->client_secret;

        // 支払い方法 配列作成
        $payments = array(
            0 => 'コンビニ払い',
            1 => 'カード支払い',
        );

        // 届け先の初期値は登録済みの住所を代入
        $post_code = $user->post_code;
        $address = $user->address;
        $building = $user->building;

        return view('purchase', compact('user', 'item_buy', 'payments', 'post_code', 'address', 'building', 'client_secret'));
    }

    /**
     * 支払いが成功した後の処理
     * - フロントで支払い成功を確認したら呼ばれる
     * - DBを更新して購入を記録
     */
    public function success(Request $request)
    {
        $user_id = Auth::id();
        $item_id = $request->item_id;

        // 商品テーブルを更新（住所なども保存する想定）
        Item::find($item_id)->update([
            'post_code' => $request->post_code,
            'address'   => $request->address,
            'building'  => $request->building,
        ]);

        // 購入テーブルにレコード追加
        Purchase::create([
            'user_id' => $user_id,
            'item_id' => $item_id,
        ]);

        // 一覧画面にリダイレクト
        return redirect()->action([ListController::class, 'index'])
            ->with('success', '購入が完了しました！');
    }
}
