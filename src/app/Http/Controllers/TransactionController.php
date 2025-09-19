<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Sell;
use App\Models\Purchase;
use App\Models\Chat;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransactionRequest;



use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index($item_id, Request $request)
    {
        $user_id = User::find(Auth::id())->id;
        $purchases = Purchase::all();
        $sells = Sell::all();
        $items = Item::all();
        $chats = Chat::all();
        $evaluation = Evaluation::all();
        $item_detail = Item::find($item_id);
        $lists = [];
        $id = 0;
        $data = $request->session()->get('chat_txt');
        $evaluated = "";

        // 自分が購入者か出品者か調べる
        $seller = $sells->where('item_id', $item_id)->where('user_id', $user_id)->first();
        $purchaser = $purchases->where('item_id', $item_id)->where('user_id', $user_id)->first();

        if (isset($purchaser)) {
            // 自分が購入者の場合、出品者のデータを取得する
            $sell = $sells->where('item_id', $item_id)->first();
            $user = User::find($sell->user_id);
            $roll = "購入者";
        } elseif (isset($seller)) {
            // 自分が出品者の場合、購入者のデータを取得する
            $purchase = $purchases->where('item_id', $item_id)->first();
            $user = User::find($purchase->user_id);
            $roll = "出品者";
        }

        // 該当商品のチャットのみ取り出す
        $chat_items = $chats->where('item_id', $item_id);

        foreach ($chat_items as $chat_item) {
            // 名前
            $chat_user = $chat_item['user_id'];
            $lists[$id]['name'] = User::all()->find($chat_user)->name;
            // アイコン
            $lists[$id]['icon'] = User::all()->find($chat_user)->profile_img;
            // 内容
            $lists[$id]['chat'] = $chat_item['chat'];
            $lists[$id]['chat_img'] = $chat_item['chat_img'];

            // 相手の書いたチャットで未読があれば既読に変更
            if (($chat_item['unread'] == false) and ($chat_item['user_id'] != $user_id)) {
                Chat::find($chat_item->id)->update([
                    'unread' => true,
                ]);
            }
            $id++;
        }

        // 購入者が評価済の場合、両者ともモーダル画面を呼び出す
        $isEmpty = $evaluation->where('item_id', $item_id)->first();

        if (isset($isEmpty->purchaser)) {
            $evaluated = "評価済";
        }
        return view('transaction', compact('item_detail', 'user', 'roll', 'items', 'lists', 'data', 'evaluated'));
    }

    public function addChat($item_id, TransactionRequest $request)
    {
        $chat_txt = $request->input('chat_txt');
        $request->session()->put('chat_txt', $chat_txt);

        // Userのid取得
        $user_id = Auth::id();
        // チャット内容
        $chat = $request['chat_txt'];
        // chatsテーブルにデータを追加する
        Chat::create([
            'user_id' => $user_id,
            'item_id' => $item_id,
            'chat' => $chat,
            'unread' => false,
        ]);

        // 画像ファイルの保存場所指定
        if (request('chat_btn')) {
            $filename = request()->file('chat_btn')->getClientOriginalName();
            $inputs['chat_img'] = request('chat_btn')->storeAs('public/images', $filename);
            // 最新のidを取得
            $new = Chat::latest()->first();
            Chat::find($new->id)->update($inputs);
        }
        return redirect()->action([TransactionController::class, 'index'], compact('item_id'));
    }

    public function updateChat($item_id, $key, Request $request)
    {
        $chats = Chat::all();
        $value = $request['hidden_value'];

        // 該当のチャットのみ取り出す
        $chat_id = $chats->where('item_id', $item_id)->skip($key)->first()->id;
        // チャットを更新
        chat::find($chat_id)->update(['chat' => $value]);
        return redirect()->action([TransactionController::class, 'index'], compact('item_id'));
    }

    public function delete($item_id, $key, Request $request)
    {
        $chats = Chat::all();
        // 該当のチャットのみ取り出す
        $chat_id = $chats->where('item_id', $item_id)->skip($key)->first()->id;
        // チャットを削除
        chat::find($chat_id)->delete();
        return redirect()->action([TransactionController::class, 'index'], compact('item_id'));
    }

    public function addEvaluation($item_id, Request $request)
    {
        $user_id = Auth::id();
        $purchases = Purchase::all();
        $sells = Sell::all();
        $evaluation = Evaluation::all();
        // 評価を取得
        $star = $request->star;

        // 自分が購入者か出品者か調べる
        $seller = $sells->where('item_id', $item_id)->where('user_id', $user_id)->first();
        $purchaser = $purchases->where('item_id', $item_id)->where('user_id', $user_id)->first();

        if (isset($purchaser)) {
            // 自分が購入者の場合、出品者のデータを取得する
            $evaluator = "購入者";
        } elseif (isset($seller)) {
            // 自分が出品者の場合、購入者のデータを取得する
            $evaluator = "出品者";
        }

        $isEmpty = $evaluation->where('item_id', $item_id)->first();

        if ($evaluator == "購入者") {
            // 自分が購入者の場合、purchaserデータに記述する
            if (isset($isEmpty)) {
                // すでに評価済の場合はデータを更新する
                Evaluation::find($isEmpty->id)->update(['purchaser' => $star]);
            } else {
                // evaluationsテーブルにデータを追加する(購入者の場合)
                Evaluation::create([
                    'item_id' => $item_id,
                    'purchaser' => $star,
                ]);
            }
        } elseif ($evaluator == "出品者") {
            // 自分が出品者の場合、sellerデータに記述する
            Evaluation::find($isEmpty->id)->update(['seller' => $star]);
        }
        return redirect()->action([ListController::class, 'index']);
    }
}
