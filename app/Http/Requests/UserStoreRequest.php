<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'patronymic' => 'nullable|string',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'date_birth' => 'required|date',
            'gender' => 'required|in:male,female'
        ];
    }
}
