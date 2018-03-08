<?php

namespace App\Http\Requests\Engagement;

use Dingo\Api\Http\FormRequest;

class CommentsGetRequest extends FormRequest
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
            'engagement_id' => 'required|integer',
            'page'          => 'required|integer',
        ];
    }
}
