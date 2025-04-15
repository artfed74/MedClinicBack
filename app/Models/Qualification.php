<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    use HasFactory;
    public $table = 'qualifications';
    protected $fillable=[
        'qualification_name',
        'description'
    ];
}
