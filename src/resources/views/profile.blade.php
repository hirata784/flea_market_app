@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div>
    <div class="img-btn">
        <div class="img-user">
            <div>
                @if ($user->profile_img === "" or $user->profile_img === null)
                <img class="profile" id="hidden" src="{{ asset('storage/images/default.png') }}" alt="プロフィール画像">
                @else
                <img class=" profile" id="hidden" src="{{ Storage::url($user['profile_img']) }}" alt="プロフィール画像">
                @endif
            </div>
            <div class="user-information">
                <div class="user-name">{{$user['nickname']}}</div>
                <div class="user-evaluation">星星星星星</div>
            </div>
        </div>
        <form class="profile-form" action="/mypage/profile">
            <button class="edit">プロフィールを編集</button>
        </form>
    </div>
    <div class="heading">
        <form class="form-exhibited" action="/mypage?tab=sell" method="get">
            <button class="btn {{($data == 'sell') ? 'choice' : 'not_choice'}}">出品した商品</button>
            <input type="hidden" name="tab" value="sell">
        </form>
        <form class="form-purchased" action="/mypage?tab=buy" method="get">
            <button class="btn {{($data == 'buy') ? 'choice' : 'not_choice'}}">購入した商品</button>
            <input type="hidden" name="tab" value="buy">
        </form>
        <form class="form-purchased" action="/mypage?tab=transaction" method="get">
            <button class="btn {{($data == 'transaction') ? 'choice' : 'not_choice'}}">取引中の商品</button>
            <input type="hidden" name="tab" value="transaction">
        </form>
    </div>
    <!-- 出品した商品のみ表示 -->
    @if ($data == 'sell')
    <div class="items">
        @foreach($items as $item)
        @if($item->sells()->where('user_id', Auth::user()->id)->exists())
        <div>
            <div class="item-list">
                <!-- ダミーの商品画像出力 -->
                @if(preg_match("/https/", $item['img_url']))
                <a href="/item/:{{ $item['id'] }}"><img class="item-img" src="{{ asset($item['img_url']) }}"></a>
                <!-- 出品した商品画像出力 -->
                @else
                <a href="/item/:{{ $item['id'] }}"><img class="item-img" src="{{ Storage::url($item['img_url']) }}"></a>
                @endif
                <span>{{$item['name']}}</span>
            </div>
        </div>
        @else
        @continue
        @endif
        @endforeach
    </div>
    @endif
    <!-- 購入した商品のみ表示 -->
    @if ($data == 'buy')
    <div class="items">
        @foreach($items as $item)
        @if($item->purchase()->where('user_id', Auth::user()->id)->exists())
        <div>
            <div class="item-list">
                <!-- ダミーの商品画像出力 -->
                @if(preg_match("/https/", $item['img_url']))
                <a href="/item/:{{ $item['id'] }}"><img class="item-img" src="{{ asset($item['img_url']) }}"></a>
                <!-- 出品した商品画像出力 -->
                @else
                <a href="/item/:{{ $item['id'] }}"><img class="item-img" src="{{ Storage::url($item['img_url']) }}"></a>
                @endif
                <span>{{$item['name']}}</span>
            </div>
        </div>
        @else
        @continue
        @endif
        @endforeach
    </div>
    @endif

    <!-- 取引中の商品のみ表示 -->
    <!-- 購入された後、出品者と購入者のリストに表示 -->
    <!-- 質問の回答次第で修正予定 -->
    @if ($data == 'transaction')
    <div class="items">
        @foreach($items as $item)
        @if(($item->purchase()->where('user_id', Auth::user()->id)->exists())
        or
        ($item->purchase()->where('item_id', $item->id)->exists())
        and
        ($item->sells()->where('user_id', Auth::user()->id)->exists())
        )
        <div>
            <div class="item-list">
                <!-- ダミーの商品画像出力 -->
                @if(preg_match("/https/", $item['img_url']))
                <a href="/transaction/:{{ $item['id'] }}"><img class="item-img" src="{{ asset($item['img_url']) }}"></a>
                <!-- 出品した商品画像出力 -->
                @else
                <a href="/transaction/:{{ $item['id'] }}"><img class="item-img" src="{{ Storage::url($item['img_url']) }}"></a>
                @endif
                <span>{{$item['name']}}</span>
            </div>
        </div>
        @else
        @continue
        @endif
        @endforeach
    </div>
    @endif


</div>
@endsection