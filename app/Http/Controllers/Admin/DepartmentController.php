<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class DepartmentController extends Controller
{
	use Upload;

	public function departmentList(Request $request)
	{
		$search = $request->all();
		$data['allDepartments'] = Department::when(isset($search['name']), function ($query) use ($search) {
			return $query->whereRaw("name REGEXP '[[:<:]]{$search['name']}[[:>:]]'");
			})
			->when(isset($search['status']) && $search['status'] == 'active', function ($q3) use ($search) {
				return $q3->where('status', 1);
			})
			->when(isset($search['status']) && $search['status'] == 'deactive', function ($q4) use ($search) {
				return $q4->where('status', 0);
			})
			->paginate(config('basic.paginate'));

		return view('admin.department.index', $data);
	}

	public function createDepartment()
	{
		return view('admin.department.create');
	}

	public function departmentStore(Request $request)
	{

		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'name' => ['required', 'max:100', 'string'],
		];

		$message = [
			'name.required' => 'Department name field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}


		$department = new Department();

		$department->name = $request->name;
		$department->status = $request->status;
		$department->save();
		return back()->with('success', 'Department Created Successfully!');
	}

	public function departmentEdit($id)
	{
		$data['singleDepartmentInfo'] = Department::findOrFail($id);
		return view('admin.department.edit', $data);
	}

	public function departmentUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'name' => ['required', 'max:100', 'string'],
		];

		$message = [
			'name.required' => 'Department name field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$department = Department::findOrFail($id);

		$department->name = $request->name;
		$department->status = $request->status;

		$department->save();
		return back()->with('success', 'Department Updated Successfully!');
	}
}
