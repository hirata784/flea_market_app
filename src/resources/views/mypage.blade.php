@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage__content">
    <div class="img_btn">
        <div class="img_user">
            <div>イメージアイコン</div>
            <div class="username">ユーザー名</div>
        </div>
        <form class="profile-form" action="/mypage/profile">
            <button class="edit">プロフィールを編集</button>
        </form>
    </div>
    <div class="mypage-form__heading">
        <form class="Products_exhibited-form" action="/" method="get">
            <button class="btn {{url()->current() == url('/sell') ? 'choice' : 'not_choice'}}">出品した商品</button>
        </form>
        <form class="Purchased_items-form" action="/tab" method="get">
            @csrf
            <button class="btn {{url()->current() == url('/buy') ? 'choice' : 'not_choice'}}">購入した商品</button>
        </form>
    </div>
</div>
@endsection