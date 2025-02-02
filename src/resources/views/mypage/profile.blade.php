@extends('layouts.app')

@section('css')
<!-- 今後作成 -->
@endsection

@section('content')
<div>プロフィール設定</div>
<form class="form" action="/logout" method="post">
    @csrf
    <button class="header-nav__button">ログアウト</button>
</form>
@endsection