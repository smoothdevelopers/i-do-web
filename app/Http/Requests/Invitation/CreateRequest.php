<?php

namespace App\Http\Requests\Invitation;

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
            'wedding_id'        => 'required|integer',
            'slots'             => 'required|integer',
            'message'           => 'string',
            'type'              => 'required|in:app,link',
            'invitee_id'        => 'required_if|type,app',
        ];
    }
}
