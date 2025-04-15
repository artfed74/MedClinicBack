<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
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
            'medication' => $this->medication,
            'comment' => $this->comment,
            'prescription_date' => $this->prescription_date,
            'doctor_full_name' => $this->doctor->user->lastname . ' ' .
                $this->doctor->user->firstname . ' ' .
                $this->doctor->user->patronymic,        ];
    }
}
