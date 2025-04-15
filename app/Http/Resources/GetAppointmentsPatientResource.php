<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAppointmentsPatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'patient_full_name' => $this->patient->user->lastname . ' ' .
                $this->patient->user->firstname . ' ' .
                $this->patient->user->patronymic,
            'medical_data' => [
                'passport_serial' => $this->patient->passport_serial,
                'passport_number' => $this->patient->passport_number,
                'policy_number' => $this->patient->policy_number,
                'policy_type' => $this->patient->policy_type,
                'blood_type' => $this->patient->medcard->blood_type,
                'diagnosis' => $this->patient->medcard->diagnosis,
                'chronic_conditions' => $this->patient->medcard->chronic_conditions,
            ],
            'doctor_full_name' => $this->doctor->user->firstname . ' ' .
                $this->doctor->user->lastname,
            'doctor_id' => $this->doctor_id,
            'service_name' => $this->service->name,
            'room_number'=>$this->room->room_number,
            'room_name'=>$this->room->room_name,
            'appointment_time' => $this->appointment_time->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'prescriptions' => PrescriptionResource::collection($this->prescriptions),

        ];
    }
}
