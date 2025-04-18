<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $table = 'services';
    protected $fillable = [
      'name',
      'price',
      'doctor_id',
    ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
