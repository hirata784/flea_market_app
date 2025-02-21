@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="list__content">
    <div class="list-form__heading">
        <form class="recommendation-form" action="/" method="get">
            <button class="btn {{url()->current() == url('/') ? 'choice' : 'not_choice'}}">おすすめ</button>
        </form>
        <form class="list-form" action="/tab" method="get">
            @csrf
            <button class="btn {{url()->current() == url('/tab') ? 'choice' : 'not_choice'}}">マイリスト</button>
        </form>
    </div>

    <div class="items">
        @foreach($items as $item)
        <!-- 未認証かつマイリストページの時は何も表示しない -->
        @if (!(Auth::check()) and Request::is('tab'))
        @continue
        @else
        <div class="items__card">
            <div class="items__card__sold">
                <a href="/item/:{{ $item['id'] }}"><img class="items__img" src="{{ asset($item['img_url']) }}"></a>
                @if($item->purchase()->where('item_id', $item->id)->exists())
                <p>Sold</p>
                @endif
            </div>
            <div class="items__name">
                <span>{{$item['name']}}</span>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>
@endsection