<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'token' => $this->resource['token'],  // Токен пользователя
            'id' => $this->resource['id'],  // ID пользователя
            'role' => $this->resource['role'],  // Роль пользователя
            'patient_id' => $this->resource['patient_id'],  // ID пациента
            'doctor_id' => $this->resource['doctor_id'],  // ID врача, если есть
            'admin_id' => $this->resource['admin_id'],  // ID админа, если есть

        ];
    }
}
