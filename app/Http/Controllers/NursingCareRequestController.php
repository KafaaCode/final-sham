<?php

namespace App\Http\Controllers;

use App\Models\NursingCareRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NursingCareRequestController extends Controller
{
    // إرسال طلب رعاية من الدكتور
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $nursingRequest = NursingCareRequest::create([
            'doctor_id' => Auth::id(),
            'patient_id' => $request->patient_id,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'تم إرسال طلب الرعاية بنجاح');
    }

    // عرض الطلبات للممرض
    public function index()
    {
        $requests = NursingCareRequest::whereNull('nurse_id')->orWhere('nurse_id', Auth::id())->orderByDesc('created_at')->get();
        return view('dashboard.nursing_requests.index', compact('requests'));
    }

    // قبول الطلب من الممرض
    public function accept($id)
    {
        $request = NursingCareRequest::findOrFail($id);
        $request->update([
            'nurse_id' => Auth::id(),
            'status' => 'accepted',
        ]);
        return redirect()->back()->with('success', 'تم قبول الطلب.');
    }

    // تسجيل إجراء تمريضي
    public function complete(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);
        $careRequest = NursingCareRequest::findOrFail($id);
        $careRequest->update([
            'status' => 'done',
        ]);
        // هنا يمكن ربط الإجراء بملف المريض أو جدول الإجراءات
        // مثال: PatientProcedure::create([...])
        return redirect()->back()->with('success', 'تم تسجيل الإجراء بنجاح.');
    }
}
