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
                <button class="transaction-btn" id="openModal">取引を完了する</button>
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
                                <div>星星星星星</div>
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
                    <img class="item-img" src="{{ asset($item_detail['img_url']) }}">
                    <!-- 出品した商品画像出力 -->
                    @else
                    <img class="item-img" src="{{ Storage::url($item_detail['img_url']) }}">
                    @endif
                </div>
                <div class="item-text">
                    <div class="item-name">{{ $item_detail['name'] }}</div>
                    <div class="item-price">{{ $item_detail['price'] }}</div>
                </div>
            </div>
            <div class="chat">
                @foreach($lists as $key => $list)
                <div class="right">
                    <div class="chat-list">
                        <div class="img-name">
                            <p>
                                @if ($list['icon'] === "" or $list['icon'] === null)
                                <img class="icon" id="icon" src="{{ asset('storage/images/default.png') }}" alt="プロフィール画像">
                                @else
                                <img class="icon" id="icon" src="{{ Storage::url($list['icon']) }}" alt="プロフィール画像">
                                @endif
                            </p>
                            <p class="chat-name">{{$list['name']}}</p>
                        </div>
                        <div class="chat-content">
                            <div>
                                @if ($list['chat_img'] !== null)
                                <img class="chat-img" src="{{ Storage::url($list['chat_img']) }}">
                                @endif
                            </div>
                            <p>
                                {{ $list['chat'] }}
                            </p>
                        </div>
                        <!-- 自分のチャットのみボタンを追加 -->
                        @if($list['name'] === Auth::user()->name)
                        <div class="my-btn">
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
                    </div>
                </div>
                @endforeach
                <img class="image" id="hidden">
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

    // 編集ボタン
    const chatTxt = document.getElementById('chat-txt');
    const chatImg = document.getElementById('chat_btn');
    const updateForms = document.querySelectorAll(".updateForm");

    updateForms.forEach(function(form) {
        form.addEventListener('submit', function() {
            const hiddenValueInput = form.querySelector('.hiddenValueInput');
            hiddenValueInput.value = chatTxt.value;
        });
    });

    // モーダル
    const openBtn = document.getElementById('openModal');
    const modal = document.getElementById('evaluation-modal');

    openBtn.addEventListener('click', function() {
        openModal();
    })

    function openModal() {
        modal.classList.add('is-open');
    }

    $('#chat-txt').keyup(function() {
        // テキストボックスに入力する度処理する
    });
</script>
@endsection