<?php

namespace App\Http\Requests\Engagement;

use Dingo\Api\Http\FormRequest;

class PhotoDeleteRequest extends FormRequest
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
            'photo_id'  => 'required|integer',
            'user_id'   => 'required|integer',
        ];
    }
}