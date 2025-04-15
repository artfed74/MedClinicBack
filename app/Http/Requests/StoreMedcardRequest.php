<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedcardRequest extends ApiRequest{

    public function rules()
    {

        return [
            'blood_type' => 'required|in:first,second,third,fourth',
            'diagnosis' => 'nullable',
            'chronic_conditions' => 'nullable',
            'notes'=> 'nullable',
            'patient_id' => 'required|unique:medcards,patient_id|exists:patients,id',
        ];
    }
}
