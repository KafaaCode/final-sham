<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ReceptionController extends Controller
{
    public function index()
    {
        $users = User::role('المريض')->get();
        return view('dashboard.reception.index', compact('users'));
    }

    public function create()
    {
        $allRoles = Role::where('name', 'المريض')->pluck('name', 'name');
        return view('dashboard.reception.create', compact('allRoles'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'current_address' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:50|unique:users,national_id',
            'place_of_birth' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'roles' => 'required|array',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('reception.index')
            ->with('success', 'تمت إضافة المستخدم بنجاح');
    }
}
