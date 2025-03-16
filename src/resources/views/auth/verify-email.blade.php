@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-content">
    <p>
        <span>登録していただいた</span><span>メールアドレスに</span><span>認証メールを</span><span>送付しました。</span>
    </p>
    <p><span>メール認証を</span><span>完了してください。</span></p>
    <form method="POST" action="/email/verification-notification">
        @csrf
        <button class="verify-btn" type="submit">
            認証メールを再送する
        </button>
    </form>
</div>
@endsection