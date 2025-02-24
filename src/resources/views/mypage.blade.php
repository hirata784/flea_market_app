@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage__content">
    <div class="img_btn">
        <div class="img_user">
            <div>イメージアイコン</div>
            <div class="username">{{Auth::user()->name}}</div>
        </div>
        <form class="profile-form" action="/mypage/profile">
            <button class="edit">プロフィールを編集</button>
        </form>
    </div>
    <div class="mypage-form__heading">
        <form class="Products_exhibited-form" action="/mypage?tab=sell" method="post">
            @csrf
            <button class="btn {{($data == 'sell') ? 'choice' : 'not_choice'}}">出品した商品</button>
        </form>
        <form class="Purchased_items-form" action="/mypage?tab=buy" method="post">
            @csrf
            <button class="btn {{($data == 'buy') ? 'choice' : 'not_choice'}}">購入した商品</button>
            <input type="hidden" name="tub" value="buy">
        </form>
    </div>

    <!-- 出品した商品のみ表示 -->
    @if ($data == 'sell')
    <div class="items">
        @foreach($items as $item)
        @if($item->sells()->where('user_id', Auth::user()->id)->exists())
        <div class="items__card">
            <div class="items__card__sold">
                <img class="items__img" src="{{ asset($item['img_url']) }}">
            </div>
            <div class="items__name">
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
        <div class="items__card">
            <div class="items__card__sold">
                <img class="items__img" src="{{ asset($item['img_url']) }}">
            </div>
            <div class="items__name">
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