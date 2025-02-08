@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<form action="/purchase" method="get">
    @csrf
    <div class="item__content">
        <div>
            <img class="item__img" src={{ $item_detail['img_url'] }} alt="">
        </div>
        <div class="detail__content">
            <div class="detail__group">
                <h1>{{ $item_detail['name'] }}</h1>
                <p>ブランド名</p>
                <div class="price">&yen;{{ $item_detail['price'] }}<span class="tax">(税込)</span></div>
                <div>いいねとコメントアイコン</div>
                <button class="btn purchase">購入手続きへ</button>
            </div>

            <div class="detail__group">
                <h2>商品説明</h2>
                <p>商品の説明表示</p>
            </div>

            <div class="detail__group">
                <h2>商品情報</h2>
                <h3>カテゴリー</h3>
                <h3>商品の状態</h3>
            </div>

            <div class="detail__group">
                <h2>コメント</h2>
                <p>コメント一覧</p>
                <h3>商品へのコメント</h3>
                <textarea class="comment-txt"></textarea>
                <div>
                    <button class="btn comment">コメントを送信する</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection