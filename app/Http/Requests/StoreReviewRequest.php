<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'review_datetime'=>'required|date_format:Y-m-d',
            'review_text'=>'required|string',
            'estimation'=>'required|in:1,2,3,4,5',
            'patient_id'=>'required|exists:patients,id'
        ];
    }
}
