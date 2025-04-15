<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SheduleStoreResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'doctor_id' => $this->doctor_id,
            "schedule" => $this->schedule
        ];
    }
}
