<?php

namespace App\Http\Requests\Wedding;

use Illuminate\Support\Facades\Input;

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
            'groom_id'          => 'required|integer',
            'bride_id'          => 'required|integer',
            'description'       => 'string',
            'venue'             => 'string',
            'reception'         => 'string',
            'venue_lat'         => 'numeric|nullable|required_with:venue_lng',
            'venue_lng'         => 'numeric|nullable|required_with:venue_lat',
            'reception_lat'     => 'numeric|nullable|required_with:reception_lng',
            'reception_lng'     => 'numeric|nullable|required_with:reception_lat',
            'when'              => 'numeric|nullable',
            'privacy'           => 'numeric|nullable',
            'image'             => 'image'
        ];
    }
}
