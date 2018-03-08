<?php

namespace App\Http\Requests\Inspiration;

use Dingo\Api\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'file'        => 'required|file',
            'description' => 'required|string',
            'media_type'  => 'required|string|in:video,image,audio',
        ];
    }
}
