<?php

namespace App\Http\Controllers;

use App\Models\Surgery;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;

class SurgeryController extends Controller
{
    public function index()
    {
        $surgeries = Surgery::with(['doctor', 'patient'])->get();
        return view('dashboard.surgeries.index', compact('surgeries'));
    }

    public function create()
    {
        $doctors = User::role('الدكتور')->get();
        $patients = User::role('المريض')->get();
        return view('dashboard.surgeries.create', compact('doctors', 'patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
            'surgery_type' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'notes' => 'nullable|string',
        ]);

        Surgery::create($request->all());

        $visit = Visit::find($request->visit_id);
        $visit->status = 0;
        $visit->save();

        return redirect()->back()->with('success', 'تمت إضافة العملية بنجاح');
    }

    public function edit($id)
    {
        $surgery = Surgery::findOrFail($id);
        $doctors = User::role('الدكتور')->get();
        $patients = User::role('المريض')->get();
        return view('dashboard.surgeries.edit', compact('surgery', 'doctors', 'patients'));
    }

    public function update(Request $request, $id)
    {
        $surgery = Surgery::findOrFail($id);
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
            'surgery_type' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'notes' => 'nullable|string',
        ]);

        $surgery->update($request->all());

        return redirect()->route('surgeries.index')->with('success', 'تم تعديل العملية بنجاح');
    }

    public function destroy($id)
    {
        $surgery = Surgery::findOrFail($id);
        $surgery->delete();
        return redirect()->route('surgeries.index')->with('success', 'تم حذف العملية بنجاح');
    }
}
