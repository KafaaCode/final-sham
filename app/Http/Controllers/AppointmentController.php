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
            'appointment_start_time' => 'required',
            'appointment_end_time' => 'required',
        ]);

        // check if start time is in last
        if (strtotime($request->appointment_start_time) < time()) {
            return back()->withErrors(['appointment_start_time' => 'وقت بداية الموعد يجب أن يكون في المستقبل'])->withInput();
        }
        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'department_id' => $request->department_id,
            'appointment_start_time' => $request->appointment_start_time,
            'appointment_end_time' => $request->appointment_end_time,
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

    // الغاء الموعد مع سبب
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'ملغي';
        $appointment->save();

        \App\Models\AppointmentCancellation::create([
            'appointment_id' => $appointment->id,
            'cancelled_by' => auth()->id(),
            'reason' => $request->reason,
        ]);

        // إشعار بسيط عبر الجلسة؛ يمكن توسيعها لإرسال إشعار للمريض أو الطبيب
        return redirect()->route('appointments.index')->with('success', 'تم إلغاء الموعد وإخطار الأطراف المعنية');
    }
}
