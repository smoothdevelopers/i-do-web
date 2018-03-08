<?php

namespace App\Http\Requests\User;

use Dingo\Api\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name'              => 'required|string|max:255',
            'gender'            => 'required|in:male,female',
            'email'             => 'required|email',
            'phone'             => 'string|nullable',
            'fb_id'             => 'string|nullable',
            'profile_pic'       => 'image'
        ];
    }
}
