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
            <div>
                <select class="payment--cb" id="sample" onchange="viewChange();">
                    <option value="" disabled selected style="display:none;">選択してください</option>
                    @foreach($payments as $payment)
                    <option value="{{ $payment }}">{{ $payment }}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="shipping_address">
            <div class="shipping_address--link">
                <h3>配送先</h3>
                <form action="/purchase/address/:{{ $item_buy['id'] }}" method="post">
                    @csrf
                    <button class="change">変更する</button>
                    <input type="hidden" name="post_code" value="{{ $post_code }}">
                    <input type="hidden" name="address" value="{{ $address }}">
                    <input type="hidden" name="building" value="{{ $building }}">
                </form>
            </div>
            <div class="address">
                <p>〒{{$post_code}}</p>
                <p>{{$address}}{{$building}}</p>
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
                <td>
                    <div id="Box1" class="my-5">
                        <p>コンビニ払い</p>
                    </div>
                    <div id="Box2" class="my-5" style="display:none;">
                        <p>カード支払い</p>
                    </div>
                </td>
            </tr>
        </table>
        <form action="/" method="post">
            @csrf
            <button class="buy-btn">
                購入する
            </button>
            <input type="hidden" name="id" value="{{ $item_buy['id'] }}">
            <input type="hidden" name="post_code" value="{{ $post_code }}">
            <input type="hidden" name="address" value="{{ $address }}">
            <input type="hidden" name="building" value="{{ $building }}">
        </form>
    </div>
</div>

<script type="text/javascript">
    function viewChange() {
        if (document.getElementById('sample')) {
            id = document.getElementById('sample').value;
            if (id == 'コンビニ払い') {
                document.getElementById('Box1').style.display = "";
                document.getElementById('Box2').style.display = "none";
            } else if (id == 'カード支払い') {
                document.getElementById('Box1').style.display = "none";
                document.getElementById('Box2').style.display = "";
            }
        }
        window.onload = viewChange;
    }
</script>
@endsection