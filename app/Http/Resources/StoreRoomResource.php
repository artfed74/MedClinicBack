<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreRoomResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'room_number' => $this->room_number,
            'room_name' => $this->room_name,
            'doctor' => [
                'id' => $this->doctor?->id,
                'fullname' => $this->doctor?->user?->lastname . ' '
                    . $this->doctor?->user?->firstname . ' '
                    . $this->doctor?->user?->patronymic,
            ],
            'type' => $this->type
        ];
    }
}
