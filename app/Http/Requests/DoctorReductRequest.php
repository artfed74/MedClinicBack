<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorReductRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'experience' => 'required|integer|min:0',
            'specialization_id' => 'required|exists:specializations,id',
            'qualification_id' => 'required|exists:qualifications,id',
            'photo' => 'nullable',
            'firstname'=>'required|string|min:3',
            'lastname' => 'required|string',
            'patronymic' => 'nullable|string',
            'date_birth' => 'nullable|date',
        ];
    }
}
