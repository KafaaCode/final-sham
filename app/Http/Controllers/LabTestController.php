<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;

class LabTestController extends Controller
{
    public function index()
    {
        $labTests = LabTest::with('patient')->get();
        return view('dashboard.lab_tests.index', compact('labTests'));
    }

    public function create()
    {
        $patients = User::role('المريض')->get();
        return view('dashboard.lab_tests.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'patient_id' => 'required|exists:users,id',
            'result' => 'required|string',
            'technical_report' => 'nullable|string',
            'technician_name' => 'required|string',
        ]);

        LabTest::create($request->all());

        $visit = Visit::find($request->visit_id);
        $visit->status = 0;
        $visit->save();

        return redirect()->back()->with('success', '✅ تم إضافة التحليل المخبري بنجاح');
    }

    public function edit($id)
    {
        $labTest = LabTest::findOrFail($id);
        $patients = User::role('المريض')->get();
        return view('dashboard.lab_tests.edit', compact('labTest', 'patients'));
    }

    public function update(Request $request, $id)
    {
        $labTest = LabTest::findOrFail($id);
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'result' => 'required|string|max:255',
            'technical_report' => 'nullable|string',
            'technician_name' => 'required|string|max:255',
        ]);

        $labTest->update($request->all());

        return redirect()->route('lab_tests.index')->with('success', 'تم تعديل التحليل المخبري بنجاح');
    }

    public function destroy($id)
    {
        $labTest = LabTest::findOrFail($id);
        $labTest->delete();
        return redirect()->route('lab_tests.index')->with('success', 'تم حذف التحليل المخبري بنجاح');
    }
}
