@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
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

            {{-- Stripeカード入力フォーム --}}
            <form id="payment-form">
                <div id="card-element"><!-- Stripeカード入力欄 --></div>
                <button id="submit" class="btn btn-primary mt-3">購入する</button>
                <div id="error-message" class="text-danger mt-2"></div>
            </form>

            {{-- Stripe.js --}}
            <script src="https://js.stripe.com/v3/"></script>
            <script>
                const stripe = Stripe("{{ env('STRIPE_KEY') }}"); // 公開可能キー
                const clientSecret = "{{ $client_secret }}"; // サーバーで作ったPaymentIntentのシークレット

                const elements = stripe.elements();
                const cardElement = elements.create("card");
                cardElement.mount("#card-element");

                const form = document.getElementById("payment-form");
                const errorMessage = document.getElementById("error-message");

                form.addEventListener("submit", async (event) => {
                    event.preventDefault();

                    const {
                        paymentIntent,
                        error
                    } = await stripe.confirmCardPayment(clientSecret, {
                        payment_method: {
                            card: cardElement,
                        }
                    });

                    if (error) {
                        errorMessage.textContent = error.message;
                    } else if (paymentIntent.status === "succeeded") {
                        // 決済成功 → サーバーに通知
                        fetch("/purchase/success", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                item_id: "{{ $item_buy->id }}",
                                post_code: "{{ $user->post_code }}",
                                address: "{{ $user->address }}",
                                building: "{{ $user->building }}"
                            })
                        }).then(() => {
                            window.location.href = "/"; // 購入完了後にリスト画面へ
                        });
                    }
                });
            </script>
            <input type="hidden" name="id" value="{{ $item_buy['id'] }}">
            <input type="hidden" name="price" value="{{ $item_buy['price'] }}">
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