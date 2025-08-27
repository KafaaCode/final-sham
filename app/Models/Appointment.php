<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'department_id',
        'appointment_start_time',
        'appointment_end_time',
        'status',
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
