@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/transaction.css') }}">
@endsection

@section('content')
<div>
    <div class="transaction-contents">
        <div class="side-bar">
            <p class="title">その他の取引</p>
        </div>
        <div class="main-contents">
            <div class="user-information">
                <div class="user-contents">
                    <div class="image">

                        @if ($user->profile_img === "" or $user->profile_img === null)
                        <img class="profile" id="hidden" src="{{ asset('storage/images/default.png') }}" alt="プロフィール画像">
                        @else
                        <img class=" profile" id="hidden" src="{{ Storage::url($user['profile_img']) }}" alt="プロフィール画像">
                        @endif

                    </div>
                    <div class="name">「{{ $user['name'] }}」さんとの取引画面</div>
                </div>
                <button class="transaction-btn">取引を完了する</button>
            </div>
            <div class="item-information">
                <div>
                    <!-- ダミーの商品画像出力 -->
                    @if(preg_match("/https/", $item_detail['img_url']))
                    <img class="item-img" src="{{ asset($item_detail['img_url']) }}">
                    <!-- 出品した商品画像出力 -->
                    @else
                    <img class="item-img" src="{{ Storage::url($item_detail['img_url']) }}">
                    @endif
                </div>
                <div class="item-text">
                    <div class="item-name">{{ $item_detail['name'] }}</div>
                    <div class="item-price">{{ $item_detail['price'] }}</div>
                </div>
            </div>
            <div class="chat">
                <div>チャット履歴</div>
            </div>
            <form class="chat-form">
                <input class="chat-txt" type="text" placeholder="取引メッセージを記入してください">
                <button class="chat-btn">画像を追加</button>
                <button class="submit">送信</button>
            </form>
        </div>

    </div>
</div>
</div>
@endsection