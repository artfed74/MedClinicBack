<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreMedcardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'blood_type' => $this->blood_type,
            'diagnosis' => $this ->diagnosis,
            'chronic_conditions'=> $this->chronic_conditions ,
            'notes' => $this->notes,
            'patient'=>$this->patient->user->lastname . ' ' . $this->patient->user->firstname . ' ' . $this->patient->user->patronymic,
        ];
    }
}
