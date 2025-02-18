@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase__contents">
    <div class="purchase__information">
        <div class="item">
            <div>
                <img class="item__img" src="{{ $item_buy['img_url'] }}" alt="">
            </div>
            <div>
                <h2>{{ $item_buy['name'] }}</h2>
                <p class=" price">&yen;{{ $item_buy['price'] }}</p>
            </div>
        </div>
        <div class="payment">
            <h3>支払い方法</h3>
            <select class="payment--cb" id="select">
                <option value="" disabled selected style="display:none;">選択してください</option>
                @foreach($payments as $payment)
                <option value="{{ $payment['method'] }}">{{ $payment['method'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="shipping_address">
            <div class="shipping_address--link">
                <h3>配送先</h3>
                <a class="change" href="/purchase/address/:{{ $item_buy['id'] }}">変更する</a>
            </div>
            <div class=" address">
                    <p>郵便番号</p>
                    <p>住所と建物</p>
            </div>
        </div>
    </div>

    <div class="purchase__confirmation">
        <table>
            <tr>
                <th>商品代金</th>
                <td>&yen;{{ $item_buy['price'] }}</td>
            </tr>
            <tr>
                <th>支払い方法</th>
                <td>作成中</td>
            </tr>
        </table>
        <div>
            <button class="buy-btn">
                購入する
            </button>
        </div>
    </div>
</div>
@endsection