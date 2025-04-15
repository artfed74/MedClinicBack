<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    public $table = 'rooms';
    protected $fillable = [
        'room_number',
        'room_name',
        'doctor_id',
        'type'

    ];
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
