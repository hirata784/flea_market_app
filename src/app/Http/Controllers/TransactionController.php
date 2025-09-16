<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Sell;
use App\Models\Purchase;
use App\Models\Chat;
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
        $item_detail = Item::find($item_id);
        $lists = [];
        $id = 0;

        // 自分が購入者か出品者か調べる
        $seller = $sells->where('item_id', $item_id)->where('user_id', $user_id)->first();
        $purchaser = $purchases->where('item_id', $item_id)->where('user_id', $user_id)->first();

        if (isset($purchaser)) {
            // 自分が購入者の場合、出品者のデータを取得する
            $sell = $sells->where('item_id', $item_id)->first();
            $user = User::find($sell->user_id);
        } elseif (isset($seller)) {
            // 自分が出品者の場合、購入者のデータを取得する
            $purchase = $purchases->where('item_id', $item_id)->first();
            $user = User::find($purchase->user_id);
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

            // 相手の書いたチャットで未読があれば既読に変更
            if (($chat_item['unread'] == false) and ($chat_item['user_id'] != $user_id)) {
                Chat::find($chat_item->id)->update([
                    'unread' => true,
                ]);
            }
            $id++;
        }
        return view('transaction', compact('item_detail', 'user', 'items', 'lists'));
    }

    public function addChat($item_id, TransactionRequest $request)
    {
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
        return redirect()->action([TransactionController::class, 'index'], compact('item_id'));
    }

    public function updateChat()
    {
        dd("編集するよ");
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
}
