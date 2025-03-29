@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form action="/" method="post">
    @csrf
    <div class="purchase-contents">
        <div class="purchase-information">
            <div class="item">
                <div>
                    <!-- ダミーの商品画像出力 -->
                    @if(preg_match("/https/", $item_buy['img_url']))
                    <img class="item-img" src="{{ asset($item_buy['img_url']) }}">
                    <!-- 出品した商品画像出力 -->
                    @else
                    <img class="item-img" src="{{ Storage::url($item_buy['img_url']) }}">
                    @endif
                </div>
                <div>
                    <h2>{{ $item_buy['name'] }}</h2>
                    <p class="price">&yen;{{ number_format($item_buy['price']) }}</p>
                </div>
            </div>
            <div class="payment">
                <h3>支払い方法</h3>
                <div>
                    <select class="payment-select" name="payment" id="sample" onchange="viewChange();">
                        <option value="" disabled selected style="display:none;">選択してください</option>
                        @foreach($payments as $payment)
                        <option value="{{ $payment }}">{{ $payment }}</option>
                        @endforeach
                    </select>
                    <div class="form-error">
                        @error('payment')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="address">
                <div class="address-btn">
                    <h3>配送先</h3>
                    <a class="change" href="/purchase/address/:{{ $item_buy['id'] }}">変更する</a>
                </div>
                <div class="address-str">
                    <div class="postcode-group">
                        <span>〒</span>
                        <input type="text" class="address-txt" name="post_code" value="{{$post_code}}" readonly>
                    </div>
                    <input type="text" class="address-txt" name="address" value="{{$address}}" readonly>
                    <input type="text" class="address-txt" name="building" value="{{$building}}" readonly>
                    <div class="form-error">
                        @error('post_code')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="form-error">
                        @error('address')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="subtotal">
            <table class="subtotal-table">
                <tr>
                    <th>商品代金</th>
                    <td>&yen;{{ $item_buy['price'] }}</td>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <td>
                        <div id="Box1" class="my-5" style="display:none;">
                            <p>コンビニ払い</p>
                        </div>
                        <div id="Box2" class="my-5" style="display:none;">
                            <p>カード支払い</p>
                        </div>
                    </td>
                </tr>
            </table>

            <form value="購入ボタン" action="{{route('stripe.charge')}}" method="POST">
                @csrf
                <script
                    src="https://checkout.stripe.com/checkout.js"
                    class="stripe-button"
                    data-key="{{ env('STRIPE_KEY') }}"
                    data-amount="{{ $item_buy['price'] }}"
                    data-name="お支払い画面"
                    data-label="購入する"
                    data-description="現在はデモ画面です"
                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                    data-locale="auto"
                    data-currency="JPY"
                    value>
                </script>
            </form>
            <input type="hidden" name="id" value="{{ $item_buy['id'] }}">
        </div>
    </div>
</form>

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