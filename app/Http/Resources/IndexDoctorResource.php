<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IndexDoctorResource extends JsonResource
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
            'lastname'=>$this->user->lastname,
            'firstname'=>$this->user->firstname,
            'patronymic'=>$this->user->patronymic,
            'expirience'=>$this->experience ?? null,
            'qualification'=>$this->qualification->qualification_name ?? null,
            'specialization'=>$this->specialization->specialization_name ?? null,
            'photo'=>$this->photo
        ];
    }
}
