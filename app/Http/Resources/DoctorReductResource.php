<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorReductResource extends JsonResource
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
            'doctor' => $this->user->lastname . ' ' . $this->user->firstname . ' ' . $this->user->patronymic,
            'specialization' => $this->specialization->specialization_name,
            'qualification' => $this->qualification->qualification_name,
            'experience' => $this->experience,
            'image_path' => $this->photo ? asset($this->photo) : null, // Полный путь к изображению
        ];
    }
}
