<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'patient_id',
        'department_id',
        'appointment_id',
        'diagnosis',
        'notes',
        'status'
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // العلاقات الجديدة
    public function xRayImages()
    {
        return $this->hasMany(XRayImage::class);
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function surgeries()
    {
        return $this->hasMany(Surgery::class);
    }
}
