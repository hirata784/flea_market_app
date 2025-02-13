@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item__content">
    <div>
        <img class="item__img" src="{{ $item_detail['img_url'] }}" alt="">
    </div>
    <div class="detail__content">
        <div class="detail__group">
            <h1>{{ $item_detail['name'] }}</h1>
            <p>ブランド名</p>
            <div class="price">&yen;{{ $item_detail['price'] }}<span class="tax">(税込)</span></div>
            <div>
                <!-- ログインしているとき(userがnullではない時) いいね使用可能 -->
                @if(!Auth::user() == null)
                @if(!Auth::user()->is_like($item_detail->id))
                <form action="/item/:{{ $item_detail['id'] }}/like" method="post">
                    @csrf
                    <div class="like__group">
                        <button class="like" type="submit">☆</button>
                        <p class="like__num">{{ $item_detail->like->count() }}</p>
                    </div>
                </form>
                @else
                <form action="/item/:{{ $item_detail['id'] }}/unlike" method="post">
                    @method('delete')
                    @csrf
                    <div class="like__group">
                        <button class="unlike" type="submit">☆</button>
                        <p class="like__num">{{ $item_detail->like->count() }}</p>
                    </div>
                </form>
                @endif
                @endif
                <!-- ログアウトしている時(userがnullの時) いいね使用不可能 -->
                @if(Auth::user() == null)
                <form action="/item/:{{ $item_detail['id'] }}/like" method="post">
                    @csrf
                    <div class="like__group">
                        <button class="like" type="submit">☆</button>
                        <p class="like__num">{{ $item_detail->like->count() }}</p>
                    </div>
                </form>
                @endif
            </div>
            <form action="/purchase/:{{ $item_detail['id'] }}" method="post">
                @csrf
                <button class="btn purchase">購入手続きへ</button>
            </form>
        </div>

        <div class="detail__group">
            <h2>商品説明</h2>
            <p>{{ $item_detail['description'] }}</p>
        </div>

        <div class="detail__group">
            <h2>商品情報</h2>
            <h3>カテゴリー</h3>
            <h3>商品の状態</h3>
        </div>

        <div class="detail__group">
            <h2>コメント({{ $item_detail->comment->count() }})</h2>
            @foreach($comments as $comment)
            <div class="comment__list">
                <p class="comment__list__name">{{$comment->user->name}}</p>
                <p class="comment__list__content">{{ $comment['comment'] }}</p>
            </div>
            @endforeach
            <h3>商品へのコメント</h3>
            <form action="/comment" method="post">
                @csrf
                <textarea class="comment-txt" name="comment"></textarea>
                <div class="form__error">
                    @error('comment')
                    {{ $message }}
                    @enderror
                </div>
                <input type="hidden" name="id" value={{ $item_detail['id'] }}>
                <div>
                    <button class="btn comment" type="submit">コメントを送信する</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection