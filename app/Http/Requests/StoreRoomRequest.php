<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'room_number' => 'required|integer|unique:rooms,room_number',
            'room_name' => 'required|string',
            'doctor_id' => 'required|integer',
            'type' => 'required',
        ];
    }
}
