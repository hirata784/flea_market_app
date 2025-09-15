<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'chat_txt' => ['required', 'max:400']

        ];
    }
    public function messages()
    {
        return [
            'chat_txt.required' => '本文を入力してください',
            'chat_txt.max' => '本文は400文字以内で入力してください',
        ];
    }
}
