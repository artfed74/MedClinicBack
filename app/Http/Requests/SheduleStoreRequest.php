<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SheduleStoreRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'doctor_id' => 'required|integer|exists:doctors,id|unique:shedules,doctor_id',
            'schedule' => 'required|array',
            'schedule.*.day_of_week' => 'required|string',
            'schedule.*.start_time' => 'required|date_format:H:i',
            'schedule.*.end_time' => 'required|date_format:H:i|after:schedule.*.start_time',
        ];
    }
}
