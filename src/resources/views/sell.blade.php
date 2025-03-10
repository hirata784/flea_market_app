@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="address__content">
    <div class="address-form__heading">
        <h2>商品の出品</h2>
    </div>
    <form class="form" action="/add" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品画像</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--btn">
                    <input class="icon__group-btn" type="file" id="fileElem" name="img_url" style="display:none" onchange="preview(this)">
                    <button id="fileSelect" type="button">ファイルを選択</button>
                    <div class="preview-area"></div>
                </div>
                <div class="form__error">
                    @error('img_url')
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
                    <input class="cb" type="checkbox" id="{{ $category['id'] }}" name="category[]" value="{{ $category['id'] }}" />
                    <label class="lb" for="{{ $category['id'] }}">{{ $category['content'] }}</label>
                    @endforeach
                </div>
                <div class="form__error">
                    @error('category')
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
                    <select class="condition--cb" name="condition" id="select">
                        <option value="" disabled selected style="display:none;">選択してください</option>
                        @foreach ($product_conditions as $product_condition)
                        <option value="{{$product_condition}}">{{$product_condition}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form__error">
                    @error('condition')
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
                    <textarea class="description" name="description" id=""></textarea>
                </div>
                <div class="form__error">
                    @error('description')
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

<script>
    function preview(elem) {
        const file = elem.files[0]
        const isOK = file?.type?.startsWith('image/')
        const image = (file && isOK) ? `<img class="img-btn" src=${URL.createObjectURL(file)}>` : ''
        elem.nextElementSibling.innerHTML = image
        // 画像選択時、デフォルトの画像を非表示にする
        hidden.style.display = "none";
    }

    const fileSelect = document.getElementById("fileSelect");
    const fileElem = document.getElementById("fileElem");

    fileSelect.addEventListener("click", (e) => {
        if (fileElem) {
            fileElem.click();
        }
    }, false);
</script>
@endsection