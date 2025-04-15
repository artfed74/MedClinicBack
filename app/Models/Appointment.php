<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'shedule_id',
        'service_id',
        'room_id',
        'appointment_time',
        'status',
    ];

    protected $casts = [
        'appointment_time' => 'datetime',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
