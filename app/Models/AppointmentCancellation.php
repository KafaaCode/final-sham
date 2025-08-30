<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentCancellation extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'cancelled_by',
        'reason',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
}
