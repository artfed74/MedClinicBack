<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'email'=>'required',
            'password'=>'required'
        ];

    }
}
