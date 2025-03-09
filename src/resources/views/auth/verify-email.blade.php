@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify__content">
    <!-- <form class="form" action="/email" method="post">
        @csrf -->
    <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
    <p>メール認証を完了してください。</p>
    <!-- <div class="form__button">
            <button class="form__button-submit" type="submit">認証はこちらから</button>
        </div>
    </form> -->

    <form class="form" method="POST" action="/email/verification-notification">
        @csrf
        <button class="verify__button" type="submit">
            認証メールを再送する
        </button>
    </form>
</div>
@endsection