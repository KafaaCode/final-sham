<?php

namespace App\Http\Controllers;

use App\Models\LabMessage;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabMessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'visit_id' => 'required|exists:visits,id',
            'test_type' => 'required|string|max:255',
            'test_details' => 'nullable|string',
            'medical_info' => 'nullable|string',
            'message' => 'nullable|string',
            'priority' => 'nullable|in:عادية,عاجلة,طارئة',
        ]);

        $labMessage = LabMessage::create([
            'patient_id' => $request->patient_id,
            'visit_id' => $request->visit_id,
            'doctor_id' => Auth::id(),
            'test_type' => $request->test_type,
            'test_details' => $request->test_details,
            'medical_info' => $request->medical_info,
            'message' => $request->message,
            'priority' => $request->priority ?? 'عادية',
            'status' => 'جديد',
        ]);

        return redirect()->back()->with('success', 'تم إرسال رسالة المخبر بنجاح');
    }

    public function markAsCompleted($id)
    {
        $message = LabMessage::findOrFail($id);
        $message->update(['status' => 'مكتمل']);

        return response()->json(['success' => true]);
    }

    public function index()
    {
        $messages = LabMessage::with(['patient', 'doctor', 'visit'])
            ->where('doctor_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('lab_messages.index', compact('messages'));
    }
}
