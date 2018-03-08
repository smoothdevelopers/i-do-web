<?php

namespace App\Http\Requests\Wedding;

use Dingo\Api\Http\FormRequest;

class CommentCreateRequest extends FormRequest
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
            'wedding_id' => 'required|integer',
            'comment'    => 'required|string'
        ];
    }
}
