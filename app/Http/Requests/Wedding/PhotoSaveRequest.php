<?php

namespace App\Http\Requests\Wedding;

use Dingo\Api\Http\FormRequest;

class PhotoSaveRequest extends FormRequest
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
            'image'         => 'required|image',
            'user_id'       => 'required|integer',
            'wedding_id'    => 'required|integer',
        ];
    }
}
