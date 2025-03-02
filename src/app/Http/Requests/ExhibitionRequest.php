<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'img_url' => ['required','mimes:jpg,png'],
            'name' => ['required'],
            'description' => ['required', 'max:255'],
            'category' => ['required'],
            'condition' => ['required'],
            'price' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'img_url.required' => '画像をアップロードしてください',
            'img_url.mimes' => '拡張子が.jpgもしくは.pngの画像を選択してください',
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'category.required' => 'カテゴリーを選択してください',
            'description.max' => '商品説明を255文字以下で入力してください',
            'price.required' => '商品価格を入力してください',
            'price.integer' => '商品価格を数値で入力してください',
            'price.min' => '商品価格を0以上で入力してください',
        ];
    }
}
