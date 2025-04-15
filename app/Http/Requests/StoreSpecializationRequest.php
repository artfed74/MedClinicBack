<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecializationRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'specialization_name'=>'required|string|unique:specializations',
            'description'=>'nullable'
        ];
    }
}
