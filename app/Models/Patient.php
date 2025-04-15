<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
   public $table = 'patients';
   protected $fillable = [
       'passport_serial',
       'passport_number',
       'policy_number',
       'policy_type',
       'user_id'
   ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function medcard()
    {
        return $this->hasOne(Medcard::class);
    }
}
