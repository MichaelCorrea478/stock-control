<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeWithdrawRequest extends FormRequest
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
            'value' => 'numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'value.numeric' => 'O valor deve ser numérico',
            'value.min' => 'O valor para saque não pode ser negativo'
        ];
    }
}
