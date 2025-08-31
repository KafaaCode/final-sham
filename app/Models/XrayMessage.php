<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XrayMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'visit_id',
        'doctor_id',
        'examination_type',
        'examination_details',
        'medical_info',
        'message',
        'priority',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
