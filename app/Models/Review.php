<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    public $table = 'reviews';
    protected $fillable = [
        'review_datetime',
        'review_text',
        'estimation',
        'patient_id'
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
