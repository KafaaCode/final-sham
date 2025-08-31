<?php

namespace App\Http\Controllers;

use App\Models\XrayMessage;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class XrayMessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'visit_id' => 'required|exists:visits,id',
            'examination_type' => 'required|string|max:255',
            'examination_details' => 'nullable|string',
            'medical_info' => 'nullable|string',
            'message' => 'nullable|string',
            'priority' => 'nullable|in:عادية,عاجلة,طارئة',
        ]);

        $xrayMessage = XrayMessage::create([
            'patient_id' => $request->patient_id,
            'visit_id' => $request->visit_id,
            'doctor_id' => Auth::id(),
            'examination_type' => $request->examination_type,
            'examination_details' => $request->examination_details,
            'medical_info' => $request->medical_info,
            'message' => $request->message,
            'priority' => $request->priority ?? 'عادية',
            'status' => 'جديد',
        ]);

        return redirect()->back()->with('success', 'تم إرسال رسالة الأشعة بنجاح');
    }

    public function markAsCompleted($id)
    {
        $message = XrayMessage::findOrFail($id);
        $message->update(['status' => 'مكتمل']);

        return response()->json(['success' => true]);
    }

    public function index()
    {
        $messages = XrayMessage::with(['patient', 'doctor', 'visit'])
            ->where('doctor_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('xray_messages.index', compact('messages'));
    }
}
