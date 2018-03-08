<?php

namespace App\Http\Requests\Engagement;

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
            'surprise_other'    => 'string|nullable',
            'proposal_plan'     => 'string|nullable',
            'groom_id'          => 'required|integer|required_without:surprise_other',
            'bride_id'          => 'required|integer|required_without:surprise_other',
            'proposal_date'     => 'date|nullable',
            'culture'           => 'integer',
            'proposal_lat'      => 'numeric|nullable|required_with:proposal_lng',
            'proposal_lng'      => 'numeric|nullable|required_with:proposal_lat',
            'proposal_place'    => 'string|nullable',
            'image'             => 'image',
            'phrase'            => 'string',
            'type'              => 'integer',
            'privacy'           => 'integer',
        ];
    }
}
