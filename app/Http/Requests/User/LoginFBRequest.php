<?php

namespace App\Http\Requests\User;

use Dingo\Api\Http\FormRequest;
use Illuminate\Support\Facades\Input;

use App\User;

class LoginFBRequest extends FormRequest
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
            'fb_id' => 'required|string',
            'name'  => 'string',
            'phone' => 'string',
            'gender' => 'string|in:male,female',
            'profile_pic' => 'image',
        ];
    }
}
