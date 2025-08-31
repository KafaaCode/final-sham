<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all(); // or use pagination if there are many users
        return view('users.index', compact('users'));
    }


    public function create()
    {
        $allRoles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('allRoles'));
    }

    public function create_user()
    {
        $allRoles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('allRoles'));
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
            'job_type' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'roles' => 'required|array',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'تمت إضافة المستخدم بنجاح');
    }


    public function update(Request $request, $id)
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
            'national_id' => 'nullable|string|max:50|unique:users,national_id,' . $id,
            'place_of_birth' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|confirmed|min:6',
            'roles' => 'required|array',
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($request->password);
        } else {
            unset($input['password']);
        }

        $user = User::findOrFail($id);
        $user->update($input);

        $user->syncRoles($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'تم تعديل المستخدم بنجاح');
    }


    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $allRoles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'allRoles', 'userRole'));
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}