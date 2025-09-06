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

        {{-- モーダルを開くトリガーだけ表示 --}}
        <button id="openModal" class="btn btn-primary">購入する</button>
        <div id="payment-error" class="payment-error text-danger mt-2"></div>
        {{-- モーダル本体（最初は非表示） --}}
        <div id="checkout-modal" class="modal-backdrop" aria-hidden="true">
            <div class="modal-card">
                <div class="modal-header">
                    <h4 style="margin:0;">お支払い</h4>
                    <button class="modal-close" id="close-checkout" aria-label="閉じる">×</button>
                </div>

                <div style="margin-bottom:10px; font-size:14px; color:#666;">
                    {{ $item_buy['name'] }} / ¥{{ number_format($item_buy['price']) }}
                </div>

                <form id="modal-payment-form">
                    <label for="modal-card-element" style="display:block; margin-bottom:8px;">カード情報</label>
                    <div id="modal-card-element" class="modal-card-element"></div>
                    <div id="modal-errors" class="modal-errors"></div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-light" id="cancel-payment">キャンセル</button>
                        <button type="submit" class="btn btn-pay" id="pay-now">支払う</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Stripe.js --}}
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe("{{ env('STRIPE_KEY') }}");
            const clientSecret = "{{ $client_secret }}";

            const elements = stripe.elements();
            const style = {
                base: {
                    color: "#32325d",
                    fontSize: "16px",
                    fontFamily: "'Helvetica Neue', Helvetica, Arial, sans-serif",
                    fontSmoothing: "antialiased",
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: "#fa755a",
                    iconColor: "#fa755a"
                }
            };

            // モーダル関連
            const modal = document.getElementById('checkout-modal');
            const openBtn = document.getElementById('openModal');
            const closeBtn = document.getElementById('close-checkout');
            const cancelBtn = document.getElementById('cancel-payment');
            const form = document.getElementById('modal-payment-form');
            const errorBox = document.getElementById('modal-errors');

            let card; // Elements インスタンス
            let mounted = false; // mount 重複防止

            function openModal() {
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                // 初回オープン時にだけ mount
                if (!mounted) {
                    card = elements.create('card', {
                        style
                    });
                    card.mount('#modal-card-element');
                    mounted = true;
                }
            }

            function closeModal() {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                errorBox.textContent = '';
            }

            // ボタンイベント
            openBtn.addEventListener('click', function() {
                const paymentMethod = document.getElementById("sample").value;
                const errorBoxSelect = document.getElementById("payment-error");

                if (!paymentMethod) {
                    errorBoxSelect.textContent = "支払い方法を選択してください。";
                    return;
                }
                errorBoxSelect.textContent = "";
                openModal(); // ← ここでモーダルを開く
            });

            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);

            // バックドロップクリックでも閉じる
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });

            // 支払い実行
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                errorBox.textContent = '';
                const payBtn = document.getElementById('pay-now');
                payBtn.disabled = true;
                payBtn.textContent = '処理中…';
                const {
                    paymentIntent,
                    error
                } = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card
                    }
                });

                if (error) {
                    errorBox.textContent = error.message;
                    payBtn.disabled = false;
                    payBtn.textContent = '支払う';
                    return;
                }

                if (paymentIntent.status === 'succeeded') {
                    // サーバーに購入確定を通知
                    await fetch("/purchase/success", {
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
                    });
                    closeModal();
                    window.location.href = "/"; // リストへ
                }
            });
        </script> <input type="hidden" name="id" value="{{ $item_buy['id'] }}">
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