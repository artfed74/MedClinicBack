<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    public $table = 'doctors';
    protected $fillable = [
        'experience',
        'user_id',
        'specialization_id',
        'qualification_id',
        'photo'
    ];
    public function user(){

        return $this->belongsTo(User::class);
    }
    public function specialization(){
        return $this->belongsTo(Specialization::class);
    }
    public function qualification(){
        return $this->belongsTo(Qualification::class);
    }
    public function services()
    {
        return $this->hasMany(Service::class);
    }
    public function schedule()
    {
        return $this->hasOne(Doctor_Shedule::class);
    }
}
