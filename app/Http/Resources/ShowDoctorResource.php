<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowDoctorResource extends JsonResource
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
            'id' => $this->id,
            'lastname' => $this->user->lastname,
            'firstname' => $this->user->firstname,
            'patronymic' => $this->user->patronymic,
            'experience' => $this->experience,
            'qualification' => [
                'id' => $this->qualification->id ?? null,
                'name' => $this->qualification->qualification_name ?? null,
            ],
            'specialization' => [
                'id' => $this->specialization->id ?? null,
                'name' => $this->specialization->specialization_name ?? null,
            ],
            'photo' => $this->photo,
            'services' => $this->services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'price' => $service->price,
                ];
            }),
            'schedule' => $this->schedule ? json_decode($this->schedule, true) : null, // Декодируем JSON в массив
        ];
    }
}
