<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreServiceResource extends JsonResource
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
            'name'=>$this->name,
            'price'=>$this->price,
            'doctor' => $this->doctor->user_id,
            'doctor_full'=>$this->doctor->user->lastname . ' ' .$this->doctor->user->firstname . ' ' . $this->doctor->user->patronymic,
        ];
    }
}
