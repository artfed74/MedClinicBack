<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medcard extends Model
{
    use HasFactory;
    protected $fillable = [
        'blood_type',
        'diagnosis',
        'chronic_conditions',
        'notes',
        'patient_id'
    ];
    protected $table = 'medcards';
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
