<?php

namespace App\Http\Requests\User;

use Dingo\Api\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'count'            => 'required_with:count|integer|max:20',
            'page'             => 'required_with:count|integer',
            'search_term'      => 'required|string',
            'gender'           => 'string|in:male,female',
            'search_by'        => 'string|in:name,phone,email',
        ];
    }
}
