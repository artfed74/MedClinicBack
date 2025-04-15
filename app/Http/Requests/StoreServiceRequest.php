<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'name'=>'required|string',
            'price'=>'required|numeric',
            'doctor_id'=>'required|integer|exists:doctors,id',
        ];
    }
}
