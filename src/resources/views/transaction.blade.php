@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
@endsection

@section('content')
<div>
    <div class="transaction-contents">
        <div class="side-bar">
            <p class="title">その他の取引</p>
            @foreach($items as $item)
            @if($item->id==$item_detail->id)
            @continue
            @else
            @if(($item->purchase()->where('user_id', Auth::user()->id)->exists())
            or
            ($item->purchase()->where('item_id', $item->id)->exists())
            and
            ($item->sells()->where('user_id', Auth::user()->id)->exists())
            )
            <div class="side-row">
                <a class="side-a" href="/transaction/:{{ $item['id'] }}">{{$item['name']}}</a>
            </div>
            @else
            @continue
            @endif
            @endif
            @endforeach
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

                @foreach($lists as $list)
                <div class="right">
                    <div class="chat-list">
                        <div class="img-name">
                            <p>
                                @if ($list['icon'] === "" or $list['icon'] === null)
                                <img class="chat-img" id="hidden" src="{{ asset('storage/images/default.png') }}" alt="プロフィール画像">
                                @else
                                <img class="chat-img" id="hidden" src="{{ Storage::url($list['icon']) }}" alt="プロフィール画像">
                                @endif
                            </p>
                            <p class="chat-name">{{$list['name']}}</p>
                        </div>
                        <p class="chat-content">{{ $list['chat'] }}</p>
                        <!-- 自分のチャットのみボタンを追加 -->
                        @if($list['name'] === Auth::user()->name)
                        <div class="my-btn">
                            <button class="edit">編集</button>
                            <button class="delete">削除</button>
                        </div>
                        @endif


                    </div>
                </div>
                @endforeach
            </div>
            <div class="form-error">
                @error('chat_txt')
                {{ $message }}
                @enderror
            </div>
            <form class="chat-form" action="/transaction/:{{ $item_detail['id'] }}/add_chat" method="post">
                @csrf
                <input class="chat-txt" name="chat_txt" type="text" placeholder="取引メッセージを記入してください">
                <button class="chat-btn">画像を追加</button>
                <input class="submit" type="image" src="{{ asset('storage/images/inputbuttun1.png') }}">
            </form>
        </div>

    </div>
</div>
</div>
@endsection