<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\XRayImage;
use App\Models\User;
use Illuminate\Http\Request;

class XRayImageController extends Controller
{
    public function index()
    {
        $xRayImages = XRayImage::with('patient')->get();
        return view('dashboard.xray_images.index', compact('xRayImages'));
    }

    public function create()
    {
        $patients = User::role('المريض')->get();
        return view('dashboard.xray_images.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'technical_report' => 'nullable|string',
            'technician_name' => 'nullable|string',
        ]);

        $path = $request->file('image_path')->store('xrays', 'public');

        XRayImage::create([
            'visit_id' => $request->visit_id,
            'patient_id' => Visit::find($request->visit_id)->patient_id,
            'image_path' => 'storage/' . $path,
            'technical_report' => $request->technical_report,
            'technician_name' => $request->technician_name,
        ]);

        $visit = Visit::find($request->visit_id);
        $visit->status = 0;
        $visit->save();

        return back()->with('success', '✅ تم إضافة صورة الأشعة بنجاح');
    }


    public function edit($id)
    {
        $xRayImage = XRayImage::findOrFail($id);
        $patients = User::role('المريض')->get();
        return view('dashboard.xray_images.edit', compact('xRayImage', 'patients'));
    }

    public function update(Request $request, $id)
    {
        $xRayImage = XRayImage::findOrFail($id);
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'technical_report' => 'nullable|string',
            'technician_name' => 'required|string|max:255',
        ]);

        $data = $request->only(['patient_id', 'technical_report', 'technician_name']);
        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('xrays', 'public');
            $data['image_path'] = 'storage/' . $path;
        }

        $xRayImage->update($data);

    return redirect()->route('xrays.index')->with('success', 'تم تعديل صورة الأشعة بنجاح');
    }

    public function destroy($id)
    {
        $xRayImage = XRayImage::findOrFail($id);
        $xRayImage->delete();
    return redirect()->route('xrays.index')->with('success', 'تم حذف صورة الأشعة بنجاح');
    }
}
