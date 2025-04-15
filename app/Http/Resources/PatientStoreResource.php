<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientStoreResource extends JsonResource
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
            // ID пациента, который хранится в таблице patients
            "patient_id" => $this->id,  // Возвращаем ID из таблицы patients
            "full_name" => $this->user->lastname . ' ' . $this->user->firstname . ' ' . $this->user->patronymic,  // Полное имя из таблицы users
            "role" => $this->user->role,  // Роль пользователя


        ];
    }
}
