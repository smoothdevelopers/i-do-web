<?php

namespace App\Http\Requests\Invitation;

use Dingo\Api\Http\FormRequest;

class SecureRequest extends FormRequest
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
            'token'             => 'required|integer',
            'secured_slots'     => 'required|integer|min:1',
        ];
    }
}
