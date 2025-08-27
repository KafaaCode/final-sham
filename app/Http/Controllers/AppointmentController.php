<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['doctor', 'department'])->get();
        return view('dashboard.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $doctors = User::role('الدكتور')->get();
        $departments = Department::all();
        return view('dashboard.appointments.create', compact('doctors', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'appointment_end_time' => 'required',
            'appointment_start_time' => 'required',
        ]);

        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'department_id' => $request->department_id,
            'appointment_end_time' => $request->appointment_end_time,
            'appointment_start_time' => $request->appointment_start_time,
            'status' => 'متوفر',
        ]);

        return redirect()->route('appointments.index')->with('success', 'تمت إضافة الموعد بنجاح');
    }


    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $doctors = User::role('الدكتور')->get();
        $departments = Department::all();
        return view('dashboard.appointments.edit', compact('appointment', 'doctors', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'appointment_end_time' => 'required',
            'appointment_start_time' => 'required',
        ]);

        $appointment->update($request->all());

        return redirect()->route('appointments.index')->with('success', 'تم تعديل الموعد بنجاح');
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'تم حذف الموعد بنجاح');
    }
}
