<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'name'=>'required|string',
            'price'=>'required|numeric',
        ];
    }
}
