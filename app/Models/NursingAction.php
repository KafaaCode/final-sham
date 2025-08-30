<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NursingAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'nursing_care_request_id',
        'nurse_id',
        'patient_id',
        'action',
    ];

    public function nursingRequest()
    {
        return $this->belongsTo(NursingCareRequest::class);
    }

    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }
}
