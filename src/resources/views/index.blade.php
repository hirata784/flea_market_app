@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="list__content">
    <div class="list-form__heading">
        <form class="form">
            <button class="btn recommendation">おすすめ</button>
            <button class="btn list">マイリスト</button>
        </form>
    </div>
    <div class="items">
        @foreach($items as $item)
        <div class="items__card">
            <a href="/item/:{{ $item['id'] }}"><img class="items__img" src="{{ asset($item['img_url']) }}"></a>
            <div class="items__name">
                <span>{{$item['name']}}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection