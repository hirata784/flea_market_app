@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify__content">
    <form class="form" action="/verify_email" method="post">
        @csrf
        <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
        <p>メール認証を完了してください。</p>

        <div class="form__button">
            <button class="form__button-submit" type="submit">認証はこちらから</button>
        </div>
    </form>

    <div class="verify__link">
        <a class="verify__button" href="/email/verification-notification">認証メールを再送する</a>
    </div>
</div>
@endsection