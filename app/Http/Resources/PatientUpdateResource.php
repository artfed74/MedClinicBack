<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientUpdateResource extends JsonResource
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
            'id'=>$this->id,
            'patient' => $this->user->lastname . ' '. $this->user->firstname . ' '.  $this->user->patronymic,
            'passport_serial'=>$this->passport_serial,
            'passport_number'=>$this->passport_number,
            'policy_number'=>$this->policy_number,
            'policy_type'=>$this->policy_type,
            'user_id' => $this->user_id
        ];
    }
}
