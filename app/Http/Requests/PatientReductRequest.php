<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientReductRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'passport_serial'=>'required|integer|min:4',
            'passport_number'=>'required|integer|min:6',
            'policy_number'=>'required|integer|min:1',
            'policy_type'=>'required|in:omc,dmc',
            'firstname'=>'required|string|min:3',
            'lastname' => 'required|string',
            'patronymic' => 'nullable|string',
            'date_birth' => 'required|date',
        ];
    }
}
