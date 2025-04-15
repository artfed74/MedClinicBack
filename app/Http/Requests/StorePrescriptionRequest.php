<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrescriptionRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'appointment_id' => 'unique:prescriptions,appointment_id',
            'doctor_id' => 'required|exists:doctors,id', // проверяем, что doctor_id существует
            'medication' => 'required|string',
            'comment' => 'nullable|string',
        ];
    }
}
