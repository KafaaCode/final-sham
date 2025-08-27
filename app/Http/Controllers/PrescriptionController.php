<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::with('patient')->get();
        return view('dashboard.prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $patients = User::role('المريض')->get();
        return view('dashboard.prescriptions.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Prescription::create($request->all());

        $visit = Visit::find($request->visit_id);
        $visit->status = 0;
        $visit->save();

        return redirect()->back()->with('success', 'تمت إضافة الوصفة الطبية بنجاح');
    }

    public function edit($id)
    {
        $prescription = Prescription::findOrFail($id);
        $patients = User::role('المريض')->get();
        return view('dashboard.prescriptions.edit', compact('prescription', 'patients'));
    }

    public function update(Request $request, $id)
    {
        $prescription = Prescription::findOrFail($id);
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $prescription->update($request->all());

        return redirect()->route('prescriptions.index')->with('success', 'تم تعديل الوصفة الطبية بنجاح');
    }

    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->delete();
        return redirect()->route('prescriptions.index')->with('success', 'تم حذف الوصفة الطبية بنجاح');
    }
}
