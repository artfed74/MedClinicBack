<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AllPatientsResource extends JsonResource
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
            'full_name' => $this->lastname . ' ' . $this->firstname . ' '. $this->patronymic,
            'email' => $this->email,
            'date_birth' => $this->date_birth,
            'gender' => $this->gender,
        ];
    }
}
