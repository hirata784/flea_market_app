@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div>
    <div class="heading">
        <h2>プロフィール設定</h2>
    </div>
    <form class="form-profile" action="/edit" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $user['id'] }}">
        <input type="hidden" name="email" value="{{ $user['email'] }}">
        <input type="hidden" name="password" value="{{ $user['password'] }}">
        <div class="icon-group">
            <input class="icon-btn" type="file" name="profile_img" onchange="preview(this)">
            <div class="preview-area"></div>
            <div>
                @if ($user->profile_img === "" or $user->profile_img === null)
                <img class="profile-img" id="hidden" src="{{ asset('storage/images/default.png') }}" alt="プロフィール画像">
                @else
                <img class="profile-img" id="hidden" src="{{ Storage::url($user['profile_img']) }}" alt="プロフィール画像">
                @endif
            </div>
        </div>
        <div class="form-error">
            @error('profile_img')
            {{ $message }}
            @enderror
        </div>
        <div class="form-group">
            <div class="form-title">
                <span class="form-span">ユーザー名</span>
            </div>
            <div>
                <div>
                    <input class="form-txt" type="text" name="nickname" value="{{ old('nickname', $user->nickname )}}" />
                </div>
                <div class="form-error">
                    @error('nickname')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-title">
                <span class="form-span">郵便番号</span>
            </div>
            <div>
                <div>
                    <input class="form-txt" type="text" name="post_code" value="{{ old('post_code', $user->post_code )}}" />
                </div>
                <div class="form-error">
                    @error('post_code')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-title">
                <span class="form-span">住所</span>
            </div>
            <div>
                <div>
                    <input class="form-txt" type="text" name="address" value="{{ old('address', $user->address )}}" />
                </div>
                <div class="form-error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-title">
                <span class="form-span">建物名</span>
            </div>
            <div>
                <div>
                    <input class="form-txt" type="text" name="building" value="{{ old('building', $user->building )}}" />
                </div>
                <div class="form-error">
                </div>
            </div>
        </div>
        <div class="form-btn">
            <button class="update-btn" type="submit">更新する</button>
        </div>
    </form>
</div>
<script>
    function preview(elem) {
        const file = elem.files[0]
        const isOK = file?.type?.startsWith('image/')
        const image = (file && isOK) ? `<img class="preview-img" src=${URL.createObjectURL(file)}>` : ''
        elem.nextElementSibling.innerHTML = image
        // 画像選択時、デフォルトの画像を非表示にする
        hidden.style.display = "none";
    }
</script>
@endsection