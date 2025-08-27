<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'first_name',
        'last_name',
        'father_name',
        'mother_name',
        'password',
        'gender',
        'phone',
        'birth_date',
        'current_address',
        'national_id',
        'job_type',
        'email',
    ];

    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'patient_id');
    }

    public function xRayImages()
    {
        return $this->hasMany(XRayImage::class, 'patient_id');
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class, 'patient_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    public function surgeriesAsDoctor()
    {
        return $this->hasMany(Surgery::class, 'doctor_id');
    }

    public function surgeriesAsPatient()
    {
        return $this->hasMany(Surgery::class, 'patient_id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'patient_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
