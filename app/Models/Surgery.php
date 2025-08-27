<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Surgery extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'visit_id',
        'patient_id',
        'surgery_type',
        'start_time',
        'end_time',
        'notes',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function procedures()
    {
        return $this->hasMany(SurgeryProcedure::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
