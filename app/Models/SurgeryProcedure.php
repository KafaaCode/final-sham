<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SurgeryProcedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'surgery_id',
        'procedure_type',
        'equipment',
    ];

    public function surgery()
    {
        return $this->belongsTo(Surgery::class);
    }
}
