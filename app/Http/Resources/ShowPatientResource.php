<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowPatientResource extends JsonResource
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
            'firstname' => $this->user->firstname,
            'lastname' => $this->user->lastname,
            'patronymic' => $this->user->patronymic,
            'email' => $this->user->email,
            'date_birth' => $this->user->date_birth,
            'gender' => $this->user->gender,
            'passport_serial'=>$this->passport_serial,
            'passport_number'=>$this->passport_number,
            'policy_number'=>$this->policy_number,
            'policy_type'=>$this->policy_type,
            'user_id' => $this->user_id,
            'medcard' => $this->medcard ? [
                'id' => $this->medcard->id,
                'blood_type' => $this->medcard->blood_type,
                'diagnosis' => $this->medcard->diagnosis,
                'chronic_conditions' => $this->medcard->chronic_conditions,
                'notes' => $this->medcard->notes,
            ] : null,
        ];
    }
}
