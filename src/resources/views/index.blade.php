@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div>
    <div class="heading">
        <form class="form-recommendation" action="/" method="get">
            @csrf
            <button class="btn {{($data == null) ? 'choice' : 'not-choice'}}">おすすめ</button>
            <input type="hidden" name="keyword">
        </form>
        <form class="form-list" action="/?tab=mylist" method="get">
            @csrf
            <button class="btn {{($data == 'mylist') ? 'choice' : 'not-choice'}}">マイリスト</button>
            <input type="hidden" name="tab" value="mylist">
            <input type="hidden" name="keyword">
        </form>
    </div>
    <!-- おすすめ -->
    @if ($data == null)
    <div class="items">
        @foreach($items as $item)
        <!-- 認証中かつ自分が出品した商品は表示しない -->
        @if(Auth::check() and ($item->sells()->where('user_id', Auth::user()->id)->exists()))
        @continue
        @else
        <div class="item-list">
            <div class="item-card">
                <!-- ダミーの商品画像出力 -->
                @if(preg_match("/https/", $item['img_url']))
                <a href="/item/:{{ $item['id'] }}"><img class="item-img" src="{{ asset($item['img_url']) }}"></a>
                <!-- 出品した商品画像出力 -->
                @else
                <a href="/item/:{{ $item['id'] }}"><img class="item-img" src="{{ Storage::url($item['img_url']) }}"></a>
                @endif
                @if($item->purchase()->where('item_id', $item->id)->exists())
                <p class="sold-str">Sold</p>
                @endif
            </div>
            <div>
                <span>{{$item['name']}}</span>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif
    <!-- マイリスト -->
    @if ($data == 'mylist')
    <div class="items">
        @foreach($items as $item)
        <!-- 未認証の時は表示しない -->
        @if (!(Auth::check()))
        @continue
        @endif
        @if($item->users()->where('user_id', Auth::user()->id)->exists())
        <div class="item-list">
            <div class="item-card">
                <!-- ダミーの商品画像出力 -->
                @if(preg_match("/https/", $item['img_url']))
                <a href="/item/:{{ $item['id'] }}"><img class="item-img" src="{{ asset($item['img_url']) }}"></a>
                <!-- 出品した商品画像出力 -->
                @else
                <a href="/item/:{{ $item['id'] }}"><img class="item-img" src="{{ Storage::url($item['img_url']) }}"></a>
                @endif
                @if($item->purchase()->where('item_id', $item->id)->exists())
                <p class="sold-str">Sold</p>
                @endif
            </div>
            <div>
                <span>{{$item['name']}}</span>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif
</div>
@endsection