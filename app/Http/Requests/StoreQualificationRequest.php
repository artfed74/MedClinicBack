<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQualificationRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'qualification_name'=>'required|unique:qualifications',
            'description'=>'nullable'

        ];
    }
}
