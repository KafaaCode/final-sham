<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class XRayImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'visit_id',
        'image_path',
        'technical_report',
        'technician_name',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
