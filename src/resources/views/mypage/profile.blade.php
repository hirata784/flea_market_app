@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile__content">
    <div class="profile-form__heading">
        <h2>プロフィール設定</h2>
    </div>
    <form class="form" action="/edit" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $user['id'] }}">
        <input type="hidden" name="email" value="{{ $user['email'] }}">
        <input type="hidden" name="password" value="{{ $user['password'] }}">

        <div class="icon__group">
            <input class="icon__group-btn" type="file" name="profile_img" onchange="preview(this)">
            <div class="preview-area"></div>
            <div class="icon__group-img">
                @if ($user->profile_img === "")
                <img class="profile" id="hidden" src="{{ asset('storage/images/default.png') }}" alt="プロフィール画像">
                @else
                <img class=" profile" id="hidden" src="{{ Storage::url($user['profile_img']) }}" alt="プロフィール画像">
                @endif
            </div>
        </div>
        <div class="form__error">
            @error('profile_img')
            {{ $message }}
            @enderror
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ユーザー名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="nickname" value="{{ old('nickname', $user->nickname )}}" />
                </div>
                <div class="form__error">
                    @error('nickname')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">郵便番号</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="post_code" value="{{ old('post_code', $user->post_code )}}" />
                </div>
                <div class="form__error">
                    @error('post_code')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">住所</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="address" value="{{ old('address', $user->address )}}" />
                </div>
                <div class="form__error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">建物名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="building" value="{{ old('building', $user->building )}}" />
                </div>
                <div class="form__error">
                </div>
            </div>
        </div>
        <div class="form__button">
            <button class="update__button" type="submit">更新する</button>
        </div>
    </form>
</div>

<script>
    function preview(elem) {
        const file = elem.files[0]
        const isOK = file?.type?.startsWith('image/')
        const image = (file && isOK) ? `<img src=${URL.createObjectURL(file)}>` : ''
        elem.nextElementSibling.innerHTML = image
        // 画像選択時、デフォルトの画像を非表示にする
        hidden.style.display = "none";
    }
</script>
@endsection