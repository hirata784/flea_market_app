@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div>
    <div class="heading">
        <h2>住所の変更</h2>
    </div>
    <form class="form-address" action="/purchase/address/:{{ $item_detail }}/update" method="post">
        @csrf
        <div class="form-group">
            <div class="form-title">
                <span class="form-span">郵便番号</span>
            </div>
            <div>
                <div>
                    <input class="form-txt" type="text" name="post_code" value="{{ old('post_code') }}" />
                </div>
                <div class="form-error">
                    @error('post_code')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-title">
                <span class="form-span">住所</span>
            </div>
            <div>
                <div>
                    <input class="form-txt" type="text" name="address" value="{{ old('address') }}" />
                </div>
                <div class="form-error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-title">
                <span class="form-span">建物名</span>
            </div>
            <div>
                <div>
                    <input class="form-txt" type="text" name="building" value="{{ old('building') }}" />
                </div>
            </div>
        </div>
        <div class="form-btn">
            <button class="update-btn" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection