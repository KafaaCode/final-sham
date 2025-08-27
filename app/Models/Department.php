<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }


    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
