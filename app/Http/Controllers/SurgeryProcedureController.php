<?php

namespace App\Http\Controllers;

use App\Models\SurgeryProcedure;
use App\Models\Surgery;
use Illuminate\Http\Request;

class SurgeryProcedureController extends Controller
{
    public function index()
    {
        $procedures = SurgeryProcedure::with('surgery')->get();
        return view('dashboard.surgery_procedures.index', compact('procedures'));
    }

    public function create()
    {
        $surgeries = Surgery::all();
        return view('dashboard.surgery_procedures.create', compact('surgeries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'surgery_id' => 'required|exists:surgeries,id',
            'procedure_type' => 'required|string|max:255',
            'equipment' => 'nullable|string|max:255',
        ]);

        SurgeryProcedure::create($request->all());

        return redirect()->back()->with('success', 'تمت إضافة الإجراء بنجاح');
    }

    public function edit($id)
    {
        $surgeryProcedure = SurgeryProcedure::findOrFail($id);
        $surgeries = Surgery::all();
        return view('dashboard.surgery_procedures.edit', compact('surgeryProcedure', 'surgeries'));
    }

    public function update(Request $request, $id)
    {
        $surgeryProcedure = SurgeryProcedure::findOrFail($id);
        $request->validate([
            'surgery_id' => 'required|exists:surgeries,id',
            'procedure_type' => 'required|string|max:255',
            'equipment' => 'nullable|string|max:255',
        ]);

        $surgeryProcedure->update($request->all());

        return redirect()->route('surgery_procedures.index')->with('success', 'تم تعديل الإجراء بنجاح');
    }

    public function destroy($id)
    {
        $surgeryProcedure = SurgeryProcedure::findOrFail($id);
        $surgeryProcedure->delete();
        return redirect()->route('surgery_procedures.index')->with('success', 'تم حذف الإجراء بنجاح');
    }
}
