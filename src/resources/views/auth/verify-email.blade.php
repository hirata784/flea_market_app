@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-content">
    <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
    <p>メール認証を完了してください。</p>

    <form method="POST" action="/email/verification-notification">
        @csrf
        <button class="verify-button" type="submit">
            認証メールを再送する
        </button>
    </form>
</div>
@endsection