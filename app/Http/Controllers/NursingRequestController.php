<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NursingCareRequest;
use App\Models\NursingAction;
use Auth;

class NursingRequestController extends Controller
{
    public function index()
    {
        $requests = NursingCareRequest::whereNull('nurse_id')
            ->orWhere('nurse_id', Auth::id())
            ->with(['patient','doctor'])
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.nursing_requests.index', compact('requests'));
    }

    public function show($id)
    {
        $request = NursingCareRequest::with(['patient','doctor','actions'])->findOrFail($id);
        return response()->json($request);
    }

    public function destroyAction($id)
    {
        $action = NursingAction::findOrFail($id);
        $action->delete();
        return response()->json(['status' => 'deleted']);
    }


    public function accept($id)
    {
        $request = NursingCareRequest::findOrFail($id);
        $request->update(['status' => 'accepted', 'nurse_id' => Auth::id()]);
        return back();
    }

    public function complete($id)
    {
        $request = NursingCareRequest::findOrFail($id);
        $request->update(['status' => 'completed']);
        return back();
    }

    // API AJAX
    public function getActions($id)
    {
        $actions = NursingAction::where('nursing_care_request_id', $id)->get();
        return response()->json($actions);
    }

    public function storeAction(Request $request)
    {
        NursingAction::create([
            'nursing_care_request_id' => $request->nursing_care_request_id,
            'nurse_id' => Auth::id(),
            'patient_id' => NursingCareRequest::find($request->nursing_care_request_id)->patient_id,
            'action' => $request->action,
        ]);
        return response()->json(['status' => 'success']);
    }

    public function updateAction(Request $request, $id)
    {
        $action = NursingAction::findOrFail($id);
        $action->update(['action' => $request->action]);
        return response()->json(['status' => 'success']);
    }
}
