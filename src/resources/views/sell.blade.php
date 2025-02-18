@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="address__content">
    <div class="address-form__heading">
        <h2>商品の出品</h2>
    </div>
    <form class="form" action="">
        @csrf
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品画像</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--btn">
                    <button class="choice_img">画像を選択する</button>
                </div>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <h3 class="subheading">商品の詳細</h3>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">カテゴリー</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--cb">
                    @foreach ($categories as $category)
                    <input class="cb" type="checkbox" id="{{ $category['id'] }}" name="{{ $category['id'] }}" />
                    <label class="lb" for="{{ $category['id'] }}">{{ $category['content'] }}</label>
                    @endforeach
                </div>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品の状態</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <select class="condition--cb" id="select">
                        <option value="" disabled selected style="display:none;">選択してください</option>
                        <option value="良好">良好</option>
                        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                        <option value="状態が悪い">状態が悪い</option>
                    </select>
                </div>
                <div class="form__error">
                    @error('brand')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <h3 class="subheading">商品名と説明</h3>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ old('name') }}" />
                </div>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ブランド名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="brand" value="{{ old('brand') }}" />
                </div>
                <div class="form__error">
                    @error('brand')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品の説明</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <textarea class="explanation" name="" id=""></textarea>
                </div>
                <div class="form__error">
                    @error('price')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">販売価格</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="price" value="{{ old('price') }}" />
                </div>
                <div class="form__error">
                    @error('price')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__button">
            <button class="list__button" type="submit">出品する</button>
        </div>
    </form>
</div>
@endsection