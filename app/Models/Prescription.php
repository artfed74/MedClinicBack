<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;
    public $table = 'prescriptions';
    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'medication',
        'comment',
        'prescription_date'

    ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
