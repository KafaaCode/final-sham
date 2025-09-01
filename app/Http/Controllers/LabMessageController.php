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
            'visit_id' => 'required|exists:visits,id',
            'message' => 'required|string',
        ]);

        $labMessage = LabMessage::create([
            'patient_id' => $request->patient_id,
            'visit_id' => $request->visit_id,
            'doctor_id' => Auth::id(),
            'message' => $request->message,
            'status' => 'جديد',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال طلب التحليل المخبري بنجاح'
        ]);
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
