@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item-content">
    <div>
        <!-- ダミーの商品画像出力 -->
        @if(preg_match("/https/", $item_detail['img_url']))
        <img class="item-img" src="{{ asset($item_detail['img_url']) }}">
        <!-- 出品した商品画像出力 -->
        @else
        <img class="item-img" src="{{ Storage::url($item_detail['img_url']) }}">
        @endif
    </div>
    <div class="detail-content">
        <div class="detail-group">
            <h1>{{ $item_detail['name'] }}</h1>
            <p>{{ $item_detail['brand'] }}</p>
            <div class="price">&yen;{{ $item_detail['price'] }}<span class="tax-str">(税込)</span></div>
            <div class="icon">
                <div>
                    <!-- ログインしているとき(userがnullではない時) いいね使用可能 -->
                    @if(!Auth::user() == null)
                    @if(!Auth::user()->is_like($item_detail->id))
                    <div class="like-group">
                        <a href="/item/:{{ $item_detail['id'] }}/like"><img class="like-icon" src="{{ asset('storage/images/like.png') }}"></a>
                        <p class="like-num">{{ $item_detail->users->count() }}</p>
                    </div>
                    @else
                    <div class="like-group">
                        <a href="/item/:{{ $item_detail['id'] }}/unlike"><img class="unlike-icon" src="{{ asset('storage/images/unlike.png') }}"></a>
                        <p class="like-num">{{ $item_detail->users->count() }}</p>
                    </div>
                    @endif
                    @endif
                    <!-- ログアウトしている時(userがnullの時) いいね使用不可能 -->
                    @if(Auth::user() == null)
                    <div class="like-group">
                        <a href="/item/:{{ $item_detail['id'] }}/like"><img class="like-icon" src="{{ asset('storage/images/like.png') }}"></a>
                        <p class="like-num">{{ $item_detail->users->count() }}</p>
                    </div>
                    @endif
                </div>
                <div class="comment-group">
                    <img class="comment-icon" src="{{ asset('storage/images/comment.png') }}">
                    <div class="comment-num">{{ $item_detail->comment->count() }}</div>
                </div>
            </div>
            <form action="/purchase/:{{ $item_detail['id'] }}" method="get">
                @csrf
                <button class="btn purchase">購入手続きへ</button>
            </form>
        </div>

        <div class="detail-group">
            <h2>商品説明</h2>
            <p>{{ $item_detail['description'] }}</p>
        </div>

        <div class="detail-group">
            <h2>商品の情報</h2>
            <div class="category-group">
                <h3>カテゴリー</h3>
                @foreach($categories as $category)
                <p class="category-content">{{$category['content']}}</p>
                @endforeach
            </div>

            <div class="condition-group">
                <h3>商品の状態</h3>
                <p class="condition-content">{{ $item_detail['condition'] }}</p>
            </div>
        </div>

        <div class="detail-group">
            <h2>コメント({{ $item_detail->comment->count() }})</h2>
            @foreach($comments as $comment)
            <div class="comment-list">
                <p class="comment-list-name">{{$comment->user->name}}</p>
                <p class="comment-list-content">{{ $comment['comment'] }}</p>
            </div>
            @endforeach
            <h3>商品へのコメント</h3>
            <form action="/comment" method="post">
                @csrf
                <textarea class="comment-txt" name="comment"></textarea>
                <div class="form-error">
                    @error('comment')
                    {{ $message }}
                    @enderror
                </div>
                <input type="hidden" name="id" value="{{ $item_detail['id'] }}">
                <div>
                    <button class="btn comment" type="submit">コメントを送信する</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection