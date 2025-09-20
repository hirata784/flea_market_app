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
                        <img class="profile" id="profile" src="{{ asset('storage/images/default.png') }}" alt="プロフィール画像">
                        @else
                        <img class="profile" id="profile" src="{{ Storage::url($user['profile_img']) }}" alt="プロフィール画像">
                        @endif
                    </div>
                    <div class="name">「{{ $user['name'] }}」さんとの取引画面</div>
                </div>
                <!-- ボタンは購入者のみ表示 -->
                @if($roll === "購入者")
                <button class="transaction-btn" id="openModal">取引を完了する</button>
                @else
                <input type="hidden" id="openModal">
                @endif
                <!-- モーダル本体(取引完了ボタンで表示) -->
                <div class="evaluation-modal" id="evaluation-modal">
                    <div class="modal-card">
                        <div class="modal-title">
                            <p class="modal-str1">取引が完了しました。</p>
                        </div>
                        <form action="/transaction/:{{ $item_detail['id'] }}/add_evaluation" method="post" id="modal-form" class="modal-form">
                            @csrf
                            <div class="modal-content">
                                <p class="modal-str2">今回の取引相手はどうでしたか？</p>
                                <div class="range-group">
                                    <input type="range" min="1" max="5" value="" class="input-range" name="star" />
                                </div>
                            </div>
                            <div class="modal-btn">
                                <button class="modal-submit">送信する</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="item-information">
                <div>
                    <!-- ダミーの商品画像出力 -->
                    @if(preg_match("/https/", $item_detail['img_url']))
                    <img class="item-img" src="{{ asset($item_detail['img_url']) }}" alt="商品画像">
                    <!-- 出品した商品画像出力 -->
                    @else
                    <img class="item-img" src="{{ Storage::url($item_detail['img_url']) }}" alt="商品画像">
                    @endif
                </div>
                <div class="item-text">
                    <div class="item-name">{{ $item_detail['name'] }}</div>
                    <div class="item-price">&yen;{{ $item_detail['price'] }}</div>
                </div>
            </div>
            <div class="chat">
                @foreach($lists as $key => $list)
                <div class="{{$lists[$key]['name'] == Auth::user()->name ? 'img-name right-icon' : 'img-name'}}">
                    <p>
                        @if ($list['icon'] === "" or $list['icon'] === null)
                        <img class="icon" id="icon" src="{{ asset('storage/images/default.png') }}" alt="プロフィール画像">
                        @else
                        <img class="icon" id="icon" src="{{ Storage::url($list['icon']) }}" alt="プロフィール画像">
                        @endif
                    </p>
                    <p class="chat-name">{{$list['name']}}</p>
                </div>
                <div class="{{$lists[$key]['name'] == Auth::user()->name ? 'chat-area right' : 'chat-area'}}">
                    <div class="chat-content">
                        <div>
                            @if ($list['chat_img'] !== null)
                            <img class="chat-img" src="{{ Storage::url($list['chat_img']) }}" alt="チャット画像">
                            @endif
                        </div>
                        <p>
                            {{ $list['chat'] }}
                        </p>
                    </div>
                </div>
                <!-- 自分のチャットのみボタンを追加 -->
                @if($list['name'] === Auth::user()->name)
                <div class="my-btn right">
                    <form class="updateForm" action="/transaction/:{{ $item_detail['id'] }}/update_chat/:{{ $key }}" method="post">
                        @csrf
                        <input type="hidden" name="hidden_value" class="hiddenValueInput">
                        <button class="edit">編集</button>
                    </form>
                    <form action="/transaction/:{{ $item_detail['id'] }}/delete/:{{ $key }}" method="post">
                        @csrf
                        <button class="delete">削除</button>
                    </form>
                </div>
                @endif
                @endforeach
                <div class="image">
                    <img id="hidden">
                </div>
            </div>
            <div class="form-error">
                @error('chat_txt')
                {{ $message }}
                @enderror
            </div>
            <div class="form-error">
                @error('chat_btn')
                {{ $message }}
                @enderror
            </div>
            <form class="form-chat" action="/transaction/:{{ $item_detail['id'] }}/add_chat" method="post" enctype="multipart/form-data">
                @csrf
                <input class="chat-txt" id="chat-txt" name="chat_txt" type="text" value="{{$data}}" placeholder="取引メッセージを記入してください">
                <input id="chat_btn" class="hidden-btn" type="file" name="chat_btn" onchange="preview(this)" hidden>
                <label for="chat_btn" class="chat-btn">画像を追加</label>
                <input class="submit" type="image" src="{{ asset('storage/images/inputbuttun1.png') }}">
            </form>
            <!-- 購入者側の評価チェック用 -->
            <span id="evaluated" data-name="{{$evaluated}}"></span>
        </div>
    </div>
</div>
<script>
    function preview(elem) {
        const file = elem.files[0];
        const isOK = file?.type?.startsWith('image/');
        const hidden = document.getElementById('hidden');

        if (file && isOK) {
            // 元画像をプレビューに差し替える
            hidden.src = URL.createObjectURL(file);
            hidden.style.display = "block";
        } else {
            hidden.style.display = "block";
            // valueをクリアして、ボタンの見た目をリセット
            elem.value = "";
        }
    }

    const chatTxt = document.getElementById('chat-txt');
    const chatImg = document.getElementById('chat_btn');
    const updateForms = document.querySelectorAll(".updateForm");
    const openBtn = document.getElementById('openModal');
    const modal = document.getElementById('evaluation-modal');

    // 編集ボタン
    updateForms.forEach(function(form) {
        form.addEventListener('submit', function() {
            const hiddenValueInput = form.querySelector('.hiddenValueInput');
            hiddenValueInput.value = chatTxt.value;
        });
    });

    // モーダル
    openBtn.addEventListener('click', function() {
        openModal();
    })

    function openModal() {
        modal.classList.add('is-open');
    }
    $(function() {
        $('.range-group').each(function() {
            for (var i = 0; i < 5; i++) {
                $(this).append('<a>');
            }
            // 初期表示は星1つ
            $(this).parent().find('a').eq(0).addClass('on');
            $(this).parent().find('.input-range').attr('value', 1);
        });
        $('.range-group>a').on('click', function() {
            var index = $(this).index();
            $(this).siblings().removeClass('on');
            for (var i = 0; i < index; i++) {
                $(this).parent().find('a').eq(i).addClass('on');
            }
            $(this).parent().find('.input-range').attr('value', index);
        });
        // 購入者が評価済であればモーダル画面をすぐ開く
        const evaluated = $('#evaluated').data();
        if (evaluated.name === '評価済') {
            modal.classList.add('is-open');
        }
    });

    // 入力時に入力値を保存
    $('#chat-txt').on('input', function() {
        localStorage.setItem('chat-txt', $(this).val());
    });
    // 保存した値を復元
    $('#chat-txt').val(localStorage.getItem('chat-txt'));
</script>
@endsection