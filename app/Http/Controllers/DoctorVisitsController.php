<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorVisitsController extends Controller
{
    public function index()
    {
        $doctorId = Auth::id();

        $visits = Visit::with(['patient', 'department', 'appointment'])
            ->whereHas('appointment', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('doctor.visits.index', compact('visits'));
    }

    public function doctorAppointments()
    {
        $doctor = auth()->user();

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->orderBy('appointment_start_time', 'asc')
            ->get();

        return view('dashboard.visits.doctor.appointments', compact('appointments'));
    }
}
