<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class RolesPermissionController extends Controller
{

	public function roleList(){
		$data['roles'] = Role::orderBy('id', 'asc')->get();
		return view('admin.role_permission.roleList', $data);
	}


	public function createRole(){
		return view('admin.role_permission.createRole');
	}

	public function roleStore(Request $request){

		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'name' => ['required'],
			'permissions' => ['required', 'array'],
			'permissions.*' => ['required'],
		];

		$message = [
			'name.required' => 'Role name field must be required',
			'permissions.required' => 'At least one menu permission is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$role = new Role();
		$role->user_id = auth()->id();
		$role->name = $request->name;
		$role->permission = (isset($request->permissions)) ? explode(',', join(',', $request->permissions)) : [];
		$role->status = $request->status;
		$role->save();

		return back()->with('success', 'New role created successfully!');
	}

	public function editRole($id){
		$data['singleRole'] = Role::findOrFail($id);
		return view('admin.role_permission.editRole', $data);

	}

	public function roleUpdate(Request $request, $id)
	{

		$purifiedData = Purify::clean($request->except('_token', '_method'));
		$rules = [
			'name' => ['required'],
			'permissions' => ['required', 'array'],
			'permissions.*' => ['required'],
		];

		$message = [
			'name.required' => 'Role name field must be required',
			'permissions.required' => 'At least one menu permission is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$role = Role::findOrFail($id);
		$role->user_id = auth()->id();
		$role->name = $request->name;
		$role->permission = (isset($request->permissions)) ? explode(',', join(',', $request->permissions)) : [];
		$role->status = $request->status;
		$role->save();

		return back()->with('success', 'Role updated successfully!');
	}


	public function deleteRole($id)
	{
		$role = Role::with(['roleUsers'])->find($id);
		if (count($role->roleUsers) > 0) {
			return back()->with('alert', 'This role has many users');
		}
		$role->delete();
		return back()->with('success', 'Delete successfully');
	}

	public function roleCreate(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'permissions' => 'required|array',
			'permissions.*' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['errors' => $validator->messages()], 422);
		}

		$role = new Role();
		$role->user_id = auth()->id();
		$role->name = $request->name;
		$role->status = $request->status;
		$role->permission = $request->permissions;

		$role->save();

		session()->flash('success', 'Saved Successfully');
		return response()->json(['result' => $role]);
	}


	public function roleDelete($id)
	{
		$role = Role::with(['roleUsers'])->find($id);
		if (count($role->roleUsers) > 0) {
			return back()->with('alert', 'This role has many users');
		}
		$role->delete();
		return back()->with('success', 'Delete successfully');
	}

	public function staffList()
	{
		$data['roleUsers'] = Admin::with('role')->where('is_owner', 0)->orderBy('role_id', 'asc')->get();
		$data['roles'] = Role::where('status', 1)->orderBy('name', 'asc')->get();
		return view('admin.role_permission.userList', $data);
	}

	public function staffCreate(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
			'username' => ['required', 'string', 'max:50', 'unique:admins,username'],
			'password' => ['required', 'string', 'min:6'],
			'role' => ['required'],
		]);

		if ($validator->fails()) {
			return response()->json(['errors' => $validator->messages()], 422);
		}

		$user = new Admin();
		$user->name = $request->name;
		$user->email = $request->email;
		$user->phone = $request->phone;
		$user->username = $request->username;
		$user->password = Hash::make($request->password);
		$user->role_id = $request->role;
		$user->status = $request->status;

		$user->save();
		session()->flash('success', 'Saved Successfully');
		return response()->json(['result' => $user]);
	}

	public function staffEdit(Request $request, $id){
		$rules = [
			'role_id' => ['required', 'exists:roles,id'],
			'name' => ['required', 'string'],
			'email' => ['required', 'email'],
			'username' => ['required', 'string', 'max:100'],
		];

		$message = [
			'role_id.required' => __('Please select a role'),
			'name.required' => __('Name field is required'),
			'email.required' => __('Email field is required'),
			'username.required' => __('Username field is required'),
		];

		$validate = Validator::make($rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->name == null){
			return back()->with('error', 'Name field is required');
		}

		if ($request->username == null){
			return back()->with('error', 'Username field is required');
		}

		if ($request->email == null){
			return back()->with('error', 'Email field is required');
		}elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
			return back()->with('error', 'Please enter a valid email address');
		}

		$staff = Admin::findOrFail($id);

		$staff->role_id = $request->role_id;
		$staff->name = $request->name;
		$staff->email = $request->email;
		$staff->phone = $request->phone;
		$staff->username = $request->username;
		$staff->status = $request->status;

		$staff->save();

		return back()->with('success', 'Staff Update Successfully');

	}

	public function statusChange($id)
	{
		$user = Admin::findOrFail($id);
		if ($user) {
			if ($user->status == 1) {
				$user->status = 0;
			} else {
				$user->status = 1;
			}
			$user->save();
			return back()->with('success', 'Updated Successfully');
		}
	}

	public function userLogin($id)
	{
		$admin = Admin::findOrFail($id);
		if ($admin->status){
			Auth::guard('admin')->loginUsingId($id);
			return redirect()->route('admin.home');
		}
		return back()->with('error', 'This user status is inactive!');
	}
}
