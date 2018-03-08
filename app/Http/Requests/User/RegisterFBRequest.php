<?php

namespace App\Http\Requests\User;

use Dingo\Api\Http\FormRequest;

class RegisterFBRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'fb_id'         => 'required|string|unique:users',
            'email'         => 'required|email|unique:users',
            'gender'        => 'required|string|in:male,female',
            'profile_pic'   => 'image',
        ];
    }
}
