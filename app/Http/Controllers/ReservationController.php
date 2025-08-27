<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['appointment', 'patient'])->get();
        return view('dashboard.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $appointments = Appointment::all();
        $patients = User::role('المريض')->get();
        return view('dashboard.reservations.create', compact('appointments', 'patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'patient_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        Reservation::create($request->all());

        return redirect()->route('reservations.index')->with('success', 'تمت إضافة الحجز بنجاح');
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $appointments = Appointment::all();
        $patients = User::role('المريض')->get();
        return view('dashboard.reservations.edit', compact('reservation', 'appointments', 'patients'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'patient_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $reservation->update($request->all());

        return redirect()->route('reservations.index')->with('success', 'تم تعديل الحجز بنجاح');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'تم حذف الحجز بنجاح');
    }
}
