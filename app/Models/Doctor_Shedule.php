<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor_Shedule extends Model
{
    use HasFactory;
    protected $table = 'shedules';

    protected $fillable = ['doctor_id', 'schedule'];

    protected $casts = [
        'schedule' => 'array',
    ];
}
