<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\Item;
use App\Models\Sell;
use App\Models\Purchase;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->tab;
        $items = Item::all();
        $user = User::find(Auth::id());
        $sells = Sell::all();
        $purchases = Purchase::all();
        $chats = Chat::all();
        $lists = [];
        $unread_sum = 0;
        $evaluation_sum = 0;
        $item_sum = 0;
        $result = 0;

        // 認証中のユーザーが購入した商品と、出品した商品を別ユーザーに購入してもらった商品を表示(総数表示の関係で最初に記述)
        $id = 0;
        // 自分が購入した商品のみ取得
        $purchase_items = $purchases->where('user_id', $user->id);
        foreach ($purchase_items as $purchase_item) {
            // 購入した商品の詳細を取得
            $item_detail[$id] = $items->find($purchase_item['item_id']);
            // 商品id
            $lists[$id]['id'] = $purchase_item['item_id'];
            // 商品名
            $lists[$id]['name'] = $item_detail[$id]->name;
            // 商品画像を取得
            $lists[$id]['img_url'] = $item_detail[$id]->img_url;

            // 相手のチャット未読数を取得
            // 該当商品(自分購入)のチャットを取得
            $chat_detail[$id] = $chats->where('item_id', $purchase_item['item_id']);
            // その中から未読のものを取得する
            $lists[$id]['read'] = $chat_detail[$id]->where('unread', false)->where('user_id', '!=', $user->id)->count();
            // 各商品の「相手から来た」最新メッセージより、updated_atを取り出す
            $updated_at = Chat::orderBy('updated_at', 'DESC')->where('item_id', $purchase_item['item_id'])->where('user_id', '!=', $user->id)->first();
            // 相手からチャットが来ていない場合は過去日付を代入
            if ($updated_at == null) {
                $lists[$id]['updated_at'] = '1000-01-01 01:00:00';
            } else {
                $lists[$id]['updated_at'] = $updated_at->updated_at->format('Y-m-d H:i:s');
            }

            // 未読数の合計
            $unread_sum += $lists[$id]['read'];
            $id++;

            // 購入者としての評価を求める
            // 出品者側からの評価(seller)を取得
            $evaluation = Evaluation::all()->where('item_id', $purchase_item['item_id'])->first();
            if ($evaluation === null) {
            } else {
                $evaluation_seller = $evaluation->seller;
                // 出品者から評価してもらっていない場合、処理しない
                if ($evaluation_seller !== null) {
                    // 評価の合計値
                    $evaluation_sum += $evaluation_seller;
                    // 評価あり商品数
                    $item_sum++;
                }
            }
        }

        // 未定義エラー対策
        $other_purchase_id = [];
        $sell_item_id = [];

        // 別ユーザーが購入した商品全てを取得
        $other_purchases = $purchases->where('user_id', '!=', $user->id);
        foreach ($other_purchases as $key => $other_purchase) {
            // そのitem_idを取得
            $other_purchase_id[$key] = $other_purchases[$key]['item_id'];
        }

        // 自分が出品した商品のみ取得
        $sell_items = $sells->where('user_id', $user->id);
        foreach ($sell_items as $key => $sell_item) {
            // そのitem_idを取得
            $sell_item_id[$key] = $sell_items[$key]['item_id'];
        }

        // 両者を比較し、重複したitem_idを取得
        $transaction_ids = array_intersect($other_purchase_id, $sell_item_id);
        foreach ($transaction_ids as $key => $transaction_id) {
            // 購入された商品の詳細を取得
            $item_detail[$id] = $items->find($transaction_id);
            // 商品id
            $lists[$id]['id'] = $transaction_id;
            // 商品名
            $lists[$id]['name'] = $item_detail[$id]->name;
            // 商品画像
            $lists[$id]['img_url'] = $item_detail[$id]->img_url;
            // 相手のチャット未読数を取得
            // 該当商品(出品商品を別ユーザーが購入)のチャットを取得
            $chat_detail[$id] = $chats->where('item_id', $transaction_id);
            // その中から未読のものを取得する
            $lists[$id]['read'] = $chat_detail[$id]->where('unread', false)->where('user_id', '!=', $user->id)->count();
            // 各商品の「相手から来た」最新メッセージより、updated_atを取り出す
            $updated_at = Chat::orderBy('updated_at', 'DESC')->where('item_id', $transaction_id)->where('user_id', '!=', $user->id)->first();
            // 相手からチャットが来ていない場合は過去日付を代入
            if ($updated_at == null) {
                $lists[$id]['updated_at'] = '1000-01-01 01:00:00';
            } else {
                $lists[$id]['updated_at'] = $updated_at->updated_at->format('Y-m-d H:i:s');
            }

            // 未読数の合計
            $unread_sum += $lists[$id]['read'];
            $id++;

            // 出品者としての評価を求める
            // 購入者側からの評価(purchaser)を取得
            $evaluation = Evaluation::all()->where('item_id', $transaction_id)->first();

            if ($evaluation === null) {
                // 出品者から評価してもらっていない場合、処理しない
            } else {
                $evaluation_purchaser = $evaluation->purchaser;
                // 評価の合計値
                $evaluation_sum += $evaluation_purchaser;
                // 評価あり商品数
                $item_sum++;
            }
        }
        // 評価あり商品が0の場合処理しない
        if ($item_sum !== 0) {
            // 足した合計を購入商品総数で割る(少数になった場合、四捨五入)
            $result = round($evaluation_sum / $item_sum);
        }

        // 更新時間より最新順に並び替え
        $ageArray = array_column($lists, 'updated_at');
        array_multisort($ageArray, SORT_DESC, $lists);

        if ($data == 'sell') {
            // 出品タブの時、認証中のユーザーが出品した商品のみ表示
            $sell_items = $sells->where('user_id', $user->id);
            foreach ($sell_items as $key => $sell_item) {
                // 出品した商品の詳細を取得
                $item_detail[$key] = $items->find($sell_item['item_id']);
                // 商品id、商品名、商品画像を取得
                $lists[$key]['id'] = $sell_item['item_id'];
                $lists[$key]['name'] = $item_detail[$key]->name;
                $lists[$key]['img_url'] = $item_detail[$key]->img_url;
            }
        } elseif ($data == 'buy') {
            // 購入タブの時、認証中のユーザーが購入した商品のみ表示
            $purchase_items = $purchases->where('user_id', $user->id);
            foreach ($purchase_items as $key => $purchase_item) {
                // 購入した商品の詳細を取得
                $item_detail[$key] = $items->find($purchase_item['item_id']);
                // 商品id、商品名、商品画像を取得
                $lists[$key]['id'] = $purchase_item['item_id'];
                $lists[$key]['name'] = $item_detail[$key]->name;
                $lists[$key]['img_url'] = $item_detail[$key]->img_url;
            }
        } elseif ($data == 'transaction') {
            // 取引中タブの時、認証中のユーザーが購入した商品と、出品した商品を別ユーザーに購入してもらった商品を表示
        }
        return view('profile', compact('data', 'user', 'lists', 'unread_sum', 'result'));
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
            return view('list', compact('items', 'user_id', 'data'));
        }
    }
}
