<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewIndexResource extends JsonResource
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
            'review_datetime' => $this->review_datetime,
            'text' => $this->review_text,
            'estimation' => $this->estimation,
            'patient' => $this->patient->user->lastname . ' ' . $this->patient->user->firstname,
        ];
    }
}
