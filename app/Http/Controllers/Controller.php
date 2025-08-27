<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function dashboard(Request $request)
    {
        $totalPatients = User::whereHas('roles', function ($query) {
            $query->where('name', 'المريض');
        })->count();

        $todayPatients = User::whereHas('roles', function ($query) {
            $query->where('name', 'المريض');
        })->whereDate('created_at', today())->count();

        $search = $request->input('search');
        $patients = User::whereHas('roles', function ($query) {
            $query->where('name', 'المريض');
        })
            ->when($search, function ($query) use ($search) {
                $query->where('national_id', 'like', "%{$search}%");
            })
            ->paginate(10);


        // الكاردات
        $departments = Department::all();
        $selectedDate = $request->input('date', date('Y-m-d'));

        $appointments = Appointment::with(['doctor', 'department'])
            ->when($request->department_id, function ($query) use ($request) {
                $query->where('department_id', $request->department_id);
            })
            ->whereDate('appointment_start_time', $selectedDate)
            ->get()
            ->map(function ($appointment) {
                if (strtotime($appointment->appointment_end_time) < time()) {
                    $appointment->status = 'محجوز';
                }
                return $appointment;
            });


        $doctorId = Auth::id();

        $visits = Visit::with(['patient', 'department', 'appointment'])
            ->whereHas('appointment', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $xray_visits = Visit::with(['patient', 'department', 'appointment'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $labTests_visits = Visit::with(['patient', 'department', 'appointment'])
            ->where('status', 2)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $surgeries_visits = Visit::with(['patient', 'department', 'appointment'])
            ->where('status', 3)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $prescriptions_visits = Visit::with(['patient', 'department', 'appointment'])
            ->where('status', 4)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $query2 = Visit::with(['patient', 'department', 'appointment.doctor', 'labTests', 'surgeries']);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = Carbon::parse($request->from_date)->startOfDay();
            $to = Carbon::parse($request->to_date)->endOfDay();
            $query2->whereBetween('created_at', [$from, $to]);
        }

        $adminVisits = $query2->get();

        // $adminVisits = Visit::with([
        //     'patient',
        //     'appointment.doctor',
        //     'department',
        //     'prescriptions',
        //     'labTests',
        //     'surgeries.procedures',
        //     'xRayImages'
        // ])->get();

        $query = Visit::with(['patient', 'department', 'appointment.doctor', 'labTests', 'surgeries']);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = Carbon::parse($request->from_date)->startOfDay();
            $to = Carbon::parse($request->to_date)->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }

        $adminVisits = $query2->get();

        $totalVisits = $adminVisits->count();
        $totalPatients = $adminVisits->pluck('patient_id')->unique()->count();
        $totalLabTests = $adminVisits->sum(fn($v) => $v->labTests->count());
        $totalSurgeries = $adminVisits->sum(fn($v) => $v->surgeries->count());
        return view('dashboard', compact(
            'totalPatients',
            'departments',
            'appointments',
            'todayPatients',
            'xray_visits',
            'labTests_visits',
            'surgeries_visits',
            'prescriptions_visits',
            'adminVisits',
            'totalVisits',
            'totalPatients',
            'totalLabTests',
            'totalSurgeries',
            'patients',
            'visits',
            'search',
            'selectedDate'
        ));
    }
}
