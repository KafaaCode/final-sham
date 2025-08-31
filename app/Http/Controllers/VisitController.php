<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\User;
use App\Models\Department;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', now()->toDateString());

        $visits = Visit::with(['patient', 'department', 'appointment.doctor', 'appointment.department'])
            ->whereHas('appointment', function ($query) use ($selectedDate) {
                $query->whereDate('appointment_start_time', $selectedDate);
            })
            ->latest()
            ->paginate(10);

        return view('dashboard.visits.index', compact('visits', 'selectedDate'));
    }

    public function xray($id)
    {
        $visit = Visit::findOrFail($id);
        if ($visit->status == 1) {
            return redirect()->back()
                ->with('error', 'تم ارسال طلب صورة من قبل انتظر الرد');
        }
        $visit->status = 1;
        $visit->save();

        return redirect()->back()
            ->with('success', 'تم ارسال طلب صورة بنجاح');
    }

    public function labTests($id)
    {
        $visit = Visit::findOrFail($id);
        if ($visit->status == 2) {
            return redirect()->back()
                ->with('error', 'تم ارسال تحليل مخبري من قبل انتظر الرد');
        }
        $visit->status = 2;
        $visit->save();

        return redirect()->back()
            ->with('success', 'تم ارسال طلب التحليل بنجاح');
    }

    public function surgeries($id)
    {
        $visit = Visit::findOrFail($id);
        if ($visit->status == 3) {
            return redirect()->back()
                ->with('error', 'تم ارسال طلب العلمية من قبل انتظر الرد');
        }
        $visit->status = 3;
        $visit->save();

        return redirect()->back()
            ->with('success', 'تم ارسال طلب العلمية بنجاح');
    }

    public function prescriptions($id)
    {
        $visit = Visit::findOrFail($id);
        if ($visit->status == 4) {
            return redirect()->back()
                ->with('error', 'تم ارسال طلب وصفة من قبل انتظر الرد');
        }
        $visit->status = 4;
        $visit->save();

        return redirect()->back()
            ->with('success', 'تم ارسال طلب الوصفة بنجاح');
    }


    public function create()
    {
        $patients = User::all();
        $departments = Department::all();
        $appointments = Appointment::all();

        return view('dashboard.visits.create', compact('patients', 'departments', 'appointments'));
    }

    public function storeForUser(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);

        if ($appointment->status != 'متوفر' || strtotime($appointment->appointment_end_time) < time()) {
            return back()->with('error', 'هذا الميعاد غير متاح للحجز.');
        }

        $conflict = Visit::where('patient_id', Auth::id())
            ->whereHas('appointment', function ($query) use ($appointment) {
                $query->whereDate('appointment_start_time', date('Y-m-d', strtotime($appointment->appointment_start_time)))
                    ->where(function ($q) use ($appointment) {
                        $q->whereBetween('appointment_start_time', [$appointment->appointment_start_time, $appointment->appointment_end_time])
                            ->orWhereBetween('appointment_end_time', [$appointment->appointment_start_time, $appointment->appointment_end_time]);
                    });
            })->exists();

        if ($conflict) {
            return back()->with('error', 'لقد قمت بحجز موعد آخر في نفس التاريخ والفترة الزمنية.');
        }

        $visit = Visit::create([
            'patient_id' => Auth::id(),
            'department_id' => $appointment->department_id,
            'appointment_id' => $appointment->id,
            'diagnosis' => $request->diagnosis,
            'notes' => $request->notes,
        ]);

        $appointment->status = 'محجوز';
        $appointment->save();

        return redirect()->back()->with('success', 'تم حجز الموعد بنجاح ✅');
    }


    public function store(Request $request)
    {
        $visit = new Visit();
        $visit->patient_id = $request->patient_id;
        $visit->department_id = $request->department_id;
        $visit->appointment_id = $request->appointment_id;
        $visit->diagnosis = $request->diagnosis;
        $visit->notes = $request->notes;
        $visit->save();

        return redirect()->route('visits.index')
            ->with('success', 'تم إضافة الزيارة بنجاح');
    }

    public function show($id)
    {
        $visit = Visit::findOrFail($id);
        $visit->load(['appointment.doctor', 'department', 'xRayImages', 'labTests', 'prescriptions', 'surgeries']);
        $nursingActions = \App\Models\NursingAction::where('patient_id', $visit->patient_id)
            ->latest()
            ->take(10)
            ->get();

        // جلب رسائل الأشعة
        $xrayMessages = \App\Models\XrayMessage::where('visit_id', $visit->id)
            ->with(['doctor'])
            ->orderBy('created_at', 'desc')
            ->get();

        // جلب رسائل المخبر
        $labMessages = \App\Models\LabMessage::where('visit_id', $visit->id)
            ->with(['doctor'])
            ->orderBy('created_at', 'desc')
            ->get();

        // جلب طلبات الرعاية التمريضية
        $nursingRequests = \App\Models\NursingCareRequest::where('patient_id', $visit->patient_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.visits.show', compact(
            'visit', 
            'nursingActions', 
            'xrayMessages', 
            'labMessages', 
            'nursingRequests'
        ));
    }


    public function edit($id)
    {
        $visit = Visit::findOrFail($id);
        $patients = User::all();
        $departments = Department::all();
        $appointments = Appointment::all();

        return view('dashboard.visits.edit', compact('visit', 'patients', 'departments', 'appointments'));
    }

    public function update(Request $request, $id)
    {
        $visit = Visit::findOrFail($id);
        $visit->patient_id = $request->patient_id;
        $visit->department_id = $request->department_id;
        $visit->appointment_id = $request->appointment_id;
        $visit->diagnosis = $request->diagnosis;
        $visit->notes = $request->notes;
        $visit->save();

        return redirect()->route('visits.index')
            ->with('success', 'تم تعديل الزيارة بنجاح');
    }

    public function destroy($id)
    {
        $visit = Visit::findOrFail($id);
        $visit->delete();

        return redirect()->route('visits.index')
            ->with('success', 'تم حذف الزيارة بنجاح');
    }

    // إلغاء الزيارة/الموعد من قبل المريض أو الطبيب (مع توثيق السبب)
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string',
        ]);

        $visit = Visit::findOrFail($id);

        if (!$visit->appointment_id) {
            return redirect()->back()->with('error', 'لا يوجد موعد مرتبط بهذه الزيارة');
        }

        $appointment = $visit->appointment;
        $appointment->status = 'ملغي';
        $appointment->save();

        \App\Models\AppointmentCancellation::create([
            'appointment_id' => $appointment->id,
            'cancelled_by' => auth()->id(),
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'تم إلغاء الموعد بنجاح');
    }

    public function myVisits()
    {
        $user = Auth::user();

        $visits = Visit::with(['department', 'appointment'])
            ->where('patient_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('dashboard.visits.my', compact('visits'));
    }



    public function exportVisitPdf($visitId)
    {
        $visit = Visit::with(['patient', 'appointment.doctor', 'department', 'prescriptions', 'labTests', 'surgeries', 'xRayImages'])->findOrFail($visitId);

        $html = view('dashboard.visits.pdf', compact('visit'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->WriteHTML($html);

        return $mpdf->Output('visit_' . $visit->id . '.pdf', 'D');
    }


}
