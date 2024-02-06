<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Branch;
use App\Models\BranchDriver;
use App\Models\BranchEmployee;
use App\Models\BranchManager;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;
use App\Traits\Upload;

class BranchController extends Controller
{
	use Upload;

	public function branchList(Request $request)
	{
		$search = $request->all();
		$data['allBranches'] = Branch::when(isset($search['name']), function ($query) use ($search) {
			return $query->whereRaw("branch_name REGEXP '[[:<:]]{$search['name']}[[:>:]]'");
		})
		->when(isset($search['phone']), function ($q) use ($search) {
			return $q->where('phone', $search['phone']);
		})
		->when(isset($search['email']), function ($q2) use ($search) {
			return $q2->where('email', $search['email']);
		})
		->when(isset($search['status']) && $search['status'] == 'active', function ($q3) use ($search) {
			return $q3->where('status', 1);
		})
		->when(isset($search['status']) && $search['status'] == 'deactive', function ($q4) use ($search) {
			return $q4->where('status', 0);
		})

		->when(isset($search['branch_type']) && $search['branch_type'] == 'head_office', function ($q5) use ($search) {
			return $q5->where('branch_type', $search['branch_type']);
		})
		->when(isset($search['branch_type']) && $search['branch_type'] == 'main_branch', function ($q6) use ($search) {
			return $q6->where('branch_type', $search['branch_type']);
		})
		->when(isset($search['branch_type']) && $search['branch_type'] == 'sub_branch', function ($q7) use ($search) {
			return $q7->where('branch_type', $search['branch_type']);
		})
		->paginate(config('basic.paginate'));

		return view('admin.branch.index', $data);
	}

	public function createBranch()
	{
		return view('admin.branch.create');
	}

	public function branchStore(Request $request)
	{

		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));

		$rules = [
			'branch_type' => ['nullable'],
			'branch_name' => ['required', 'max:60', 'string'],
			'email' => ['email', 'nullable', 'max:100'],
			'phone' => ['numeric', 'nullable'],
			'address' => ['required', 'max:2000'],
			'details' => ['required', 'max:2000'],
			'image' => ['nullable', 'max:3072', 'mimes:jpg,jpeg,png']
		];

		$message = [
			'branch_name.required' => 'Branch name field is required',
			'address.required' => 'Address field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}


		$branch = new Branch();

		$branch->branch_name = $request->branch_name;
		$branch->email = $request->email;
		$branch->phone = $request->phone;
		$branch->address = $request->address;
		$branch->details = $request->details;
		$branch->status = $request->status;

		if ($request->hasFile('image')) {
			try {
				$image = $this->fileUpload($request->image, config('location.branch.path'));
				if ($image) {
					$branch->image = $image['path'] ?? null;
					$branch->driver = $image['driver'] ?? null;
				}
			} catch (\Exception $exp) {
				return back()->with('error', 'Image could not be uploaded.');
			}
		}

		$branch->save();
		return back()->with('success', 'Branch Created Successfully!');
	}

	public function branchEdit($id)
	{
		$data['singleBranchInfo'] = Branch::findOrFail($id);
		return view('admin.branch.edit', $data);
	}

	public function branchUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));

		$rules = [
			'branch_name' => ['required', 'max:60', 'string'],
			'email' => ['email', 'nullable', 'max:100'],
			'phone' => ['numeric', 'nullable'],
			'address' => ['required', 'max:2000'],
			'details' => ['required', 'max:2000'],
			'image' => ['nullable', 'max:3072', 'mimes:jpg,jpeg,png']
		];

		$message = [
			'branch_name.required' => 'Branch name field is required',
			'address.required' => 'Address field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$branch = Branch::findOrFail($id);

		$branch->branch_name = $request->branch_name;
		$branch->email = $request->email;
		$branch->phone = $request->phone;
		$branch->address = $request->address;
		$branch->details = $request->details;
		$branch->status = $request->status;

		if ($request->hasFile('image')) {
			try {
				$image = $this->fileUpload($request->image, config('location.branch.path'));
				if ($image) {
					$branch->image = $image['path'] ?? null;
					$branch->driver = $image['driver'] ?? null;
				}
			} catch (\Exception $exp) {
				return back()->with('error', 'Image could not be uploaded.');
			}
		}

		$branch->save();
		return back()->with('success', 'Branch Updated Successfully!');
	}

	public function showBranchProfile($id)
	{
		$data['branchInfo'] = Branch::with('transaction')
			->withCount('transaction')
			->withCount('shipments')
			->withCount([
				'transaction as total_transactions' => function ($query) use ($id) {
					$query->where('branch_id', $id)
						->where('trx_type', '+')->orWhere('trx_type', '-')
						->select(DB::raw('SUM(CASE WHEN condition_receive_payment_by_receiver_branch = 1 THEN amount + condition_receive_amount ELSE amount END) as total_amount'));
				},
				'transaction as total_condition_receive_amount' => function ($query) use ($id) {
					$query->where('branch_id', $id)
						->where('trx_type', '+')
						->select(DB::raw('SUM(CASE WHEN condition_receive_payment_by_receiver_branch = 1 AND condition_receive_payment_by_sender_branch = 0 THEN condition_receive_amount ELSE 0 END) as total_condition_receive_amount'));
				},
				'transaction as total_condition_pay_amount' => function ($query) use ($id) {
					$query->where('branch_id', $id)
						->where('trx_type', '-')
						->select(DB::raw('SUM(CASE WHEN condition_receive_payment_by_receiver_branch = 1 AND condition_receive_payment_by_sender_branch = 1 THEN condition_receive_amount ELSE 0 END) as total_condition_pay_amount'));
				},
			])
			->where('status', 1)
			->findOrFail($id);

		$data['totalShipments'] = $data['branchInfo']->shipments_count;
		$data['totalTransactions'] = $data['branchInfo']->total_transactions;
		$data['conditionReceiveAmount'] = $data['branchInfo']->total_condition_receive_amount;
		$data['conditionPayAmount'] = $data['branchInfo']->total_condition_pay_amount;
		$data['branchInTransaction'] = $data['totalTransactions'] - $data['conditionPayAmount'];
		$data['branchOutTransaction'] = $data['branchInfo']->total_condition_pay_amount;
		$data['branchCurrentAssets'] = $data['branchInTransaction'] - $data['branchOutTransaction'];

		return view('admin.branch.profile', $data);
	}


	public function branchManagerList(Request $request)
	{
		$search = $request->all();

		$data['allBranchManagers'] = BranchManager::with('branch', 'admin')
			->when(isset($search['manager']), function ($query) use ($search) {
				return $query->whereHas('admin', function ($q) use ($search) {
					$q->whereRaw("name REGEXP '[[:<:]]{$search['manager']}[[:>:]]'");
				});
			})
			->when(isset($search['branch']), function ($query) use ($search) {
				return $query->whereHas('branch', function ($q) use ($search) {
					$q->whereRaw("branch_name REGEXP '[[:<:]]{$search['branch']}[[:>:]]'");
				});
			})
			->when(isset($search['phone']), function ($q) use ($search) {
				return $q->where('phone', $search['phone']);
			})
			->when(isset($search['email']), function ($q2) use ($search) {
				return $q2->where('email', $search['email']);
			})
			->when(isset($search['status']) && $search['status'] == 'active', function ($q3) use ($search) {
				return $q3->where('status', 1);
			})
			->when(isset($search['status']) && $search['status'] == 'deactive', function ($q4) use ($search) {
				return $q4->where('status', 0);
			})
			->paginate(config('basic.paginate'));
		return view('admin.branchManager.index', $data);
	}

	public function createBranchManager()
	{
		$data['allBranches'] = Branch::where('status', 1)->get();
		$data['allRoles'] = Role::where('status', 1)->get();
		return view('admin.branchManager.create', $data);
	}

	public function branchManagerStore(Request $request)
	{

		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));

		$rules = [
			'branch_id' => ['required', 'exists:branches,id'],
			'role_id' => ['required', 'exists:roles,id'],
			'branch_manager_id' => ['required', 'exists:admins,id'],
			'email' => ['required', 'email'],
			'phone' => ['required', 'numeric', 'nullable'],
			'address' => ['required', 'max:2000'],
			'national_id' => ['nullable', 'max:100'],
			'image' => ['nullable', 'max:3072', 'mimes:jpg,jpeg,png']
		];

		$message = [
			'branch_id.required' => 'Please select a branch',
			'role_id.required' => 'Please select a role',
			'email.required' => 'Email field is required',
			'phone.required' => 'Phone Number is required',
			'address.required' => 'Address field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$branchManager = new BranchManager();

		$branchManager->branch_id = $request->branch_id;
		$branchManager->role_id = $request->role_id;
		$branchManager->admin_id = $request->branch_manager_id;
		$branchManager->email = $request->email;
		$branchManager->phone = $request->phone;
		$branchManager->address = $request->address;
		$branchManager->national_id = $request->national_id;
		$branchManager->status = $request->status;

		if ($request->hasFile('image')) {
			try {
				$image = $this->fileUpload($request->image, config('location.branchManager.path'));
				if ($image) {
					$branchManager->image = $image['path'] ?? null;
					$branchManager->driver = $image['driver'] ?? null;
				}
			} catch (\Exception $exp) {
				return back()->with('error', 'Image could not be uploaded.');
			}
		}

		$branchManager->save();
		return back()->with('success', 'Branch Manager Created Successfully!');
	}

	public function branchManagerEdit($id)
	{
		$data['allBranches'] = Branch::where('status', 1)->get();
		$data['allRoles'] = Role::where('status', 1)->get();
		$data['singleBranchManagerInfo'] = BranchManager::with('branch', 'admin')->findOrFail($id);
		$data['allManagers'] = Admin::where('role_id', $data['singleBranchManagerInfo']->role_id)->get();

		return view('admin.branchManager.edit', $data);
	}

	public function branchManagerUpdate(Request $request, $id)
	{

		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));

		$rules = [
			'branch_id' => ['required', 'exists:branches,id'],
			'role_id' => ['required', 'exists:roles,id'],
			'branch_manager_id' => ['required', 'exists:admins,id'],
			'email' => ['required', 'email'],
			'phone' => ['required', 'numeric', 'nullable'],
			'address' => ['required', 'max:2000'],
			'national_id' => ['nullable', 'max:100'],
			'image' => ['nullable', 'max:3072', 'mimes:jpg,jpeg,png']
		];

		$message = [
			'branch_id.required' => 'Please select a branch',
			'role_id.required' => 'Please select a role',
			'email.required' => 'Email field is required',
			'phone.required' => 'Phone Number is required',
			'address.required' => 'Address field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$branchManager = BranchManager::findOrFail($id);

		$branchManager->branch_id = $request->branch_id;
		$branchManager->role_id = $request->role_id;
		$branchManager->admin_id = $request->branch_manager_id;
		$branchManager->email = $request->email;
		$branchManager->phone = $request->phone;
		$branchManager->address = $request->address;
		$branchManager->national_id = $request->national_id;
		$branchManager->status = $request->status;

		if ($request->hasFile('image')) {
			try {
				$image = $this->fileUpload($request->image, config('location.branchManager.path'));
				if ($image) {
					$branchManager->image = $image['path'] ?? null;
					$branchManager->driver = $image['driver'] ?? null;
				}
			} catch (\Exception $exp) {
				return back()->with('error', 'Image could not be uploaded.');
			}
		}

		$branchManager->save();
		return back()->with('success', 'Branch Manager Updated Successfully!');
	}

	public function branchEmployeeList(Request $request)
	{
		$search = $request->all();
		$authenticateUser = Auth::guard('admin')->user();

		$data['branchEmployees'] = BranchEmployee::with('branch.branchManager', 'admin', 'department')
			->when(isset($authenticateUser->role_id), function ($query) use ($authenticateUser) {
				return $query->whereHas('branch.branchManager', function ($qry) use ($authenticateUser) {
					$qry->where(['admin_id' => $authenticateUser->id]);
				});
			})
			->when(isset($search['branch']), function ($query) use ($search) {
				return $query->whereHas('branch', function ($q) use ($search) {
					$q->whereRaw("branch_name REGEXP '[[:<:]]{$search['branch']}[[:>:]]'");
				});
			})
			->when(isset($search['department']), function ($query) use ($search) {
				return $query->whereHas('department', function ($q) use ($search) {
					$q->whereRaw("name REGEXP '[[:<:]]{$search['department']}[[:>:]]'");
				});
			})
			->when(isset($search['phone']), function ($q) use ($search) {
				return $q->where('phone', $search['phone']);
			})
			->when(isset($search['email']), function ($q2) use ($search) {
				return $q2->where('email', $search['email']);
			})
			->when(isset($search['status']) && $search['status'] == 'active', function ($q3) use ($search) {
				return $q3->where('status', 1);
			})
			->when(isset($search['status']) && $search['status'] == 'deactive', function ($q4) use ($search) {
				return $q4->where('status', 0);
			})
			->paginate(config('basic.paginate'));

		return view('admin.branchEmployee.index', $data, compact('authenticateUser'));
	}

	public function createEmployee()
	{
		$authenticateUser = Auth::guard('admin')->user();
		$data['allBranches'] = Branch::with('branchManager')
			->when(isset($authenticateUser->role_id), function ($query) use ($authenticateUser) {
				return $query->whereHas('branchManager', function ($q) use ($authenticateUser) {
					$q->where('admin_id', $authenticateUser->id);
				});
			})
			->where('status', 1)->get();

		$data['allRoles'] = Role::where('status', 1)->get();
		$data['allDepartments'] = Department::where('status', 1)->get();
		return view('admin.branchEmployee.create', $data, compact('authenticateUser'));
	}


	public function branchEmployeeStore(Request $request)
	{

		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));

		$rules = [
			'branch_id' => ['required', 'exists:branches,id'],
			'role_id' => ['required', 'exists:roles,id'],
			'branch_employee_id' => ['required', 'exists:admins,id'],
			'department_id' => ['required', 'exists:departments,id'],
			'email' => ['required', 'email', 'nullable'],
			'phone' => ['required', 'numeric', 'nullable'],
			'address' => ['required', 'max:2000'],
			'national_id' => ['nullable', 'max:100'],
			'image' => ['nullable', 'max:3072', 'mimes:jpg,jpeg,png']
		];

		$message = [
			'branch_id.required' => 'Please select a branch',
			'role_id.required' => 'Please select a role',
			'branch_employee_id.required' => 'Please select a employee',
			'department_id.required' => 'Please select a department',
			'email.required' => 'Email field is required',
			'phone.required' => 'Phone Number is required',
			'address.required' => 'Address field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$branchEmployee = new BranchEmployee();

		$branchEmployee->branch_id = $request->branch_id;
		$branchEmployee->role_id = $request->role_id;
		$branchEmployee->admin_id = $request->branch_employee_id;
		$branchEmployee->department_id = $request->department_id;
		$branchEmployee->email = $request->email;
		$branchEmployee->phone = $request->phone;
		$branchEmployee->address = $request->address;
		$branchEmployee->national_id = $request->national_id;
		$branchEmployee->status = $request->status;

		if ($request->hasFile('image')) {
			try {
				$image = $this->fileUpload($request->image, config('location.branchEmployee.path'));
				if ($image) {
					$branchEmployee->image = $image['path'] ?? null;
					$branchEmployee->driver = $image['driver'] ?? null;
				}
			} catch (\Exception $exp) {
				return back()->with('error', 'Image could not be uploaded.');
			}
		}

		$branchEmployee->save();
		return back()->with('success', 'Branch Employee Created Successfully!');
	}


	public function branchEmployeeEdit($id)
	{
		$authenticateUser = Auth::guard('admin')->user();
		$data['allBranches'] = Branch::with('branchManager')
			->when(isset($authenticateUser->role_id), function ($query) use ($authenticateUser) {
				return $query->whereHas('branchManager', function ($q) use ($authenticateUser) {
					$q->where('admin_id', $authenticateUser->id);
				});
			})
			->where('status', 1)->get();

		$data['allRoles'] = Role::where('status', 1)->get();
		$data['allDepartments'] = Department::where('status', 1)->get();
		$data['singleBranchEmployeeInfo'] = BranchEmployee::with('branch', 'admin', 'department')->where('status', 1)
			->when(isset($authenticateUser->role_id), function ($query) use ($authenticateUser) {
				return $query->whereHas('branch.branchManager', function ($qry) use ($authenticateUser) {
					$qry->where(['admin_id' => $authenticateUser->id]);
				});
			})
			->findOrFail($id);
		$data['allEmployees'] = Admin::where('role_id', $data['singleBranchEmployeeInfo']->role_id)->where('status', 1)->get();

		return view('admin.branchEmployee.edit', $data, compact('authenticateUser'));
	}


	public function branchEmployeeUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));

		$rules = [
			'branch_id' => ['required', 'exists:branches,id'],
			'role_id' => ['required', 'exists:roles,id'],
			'branch_employee_id' => ['required', 'exists:admins,id'],
			'department_id' => ['required', 'exists:departments,id'],
			'email' => ['required', 'email', 'nullable'],
			'phone' => ['required', 'numeric', 'nullable'],
			'address' => ['required', 'max:2000'],
			'national_id' => ['nullable', 'max:100'],
			'image' => ['nullable', 'max:3072', 'mimes:jpg,jpeg,png']
		];

		$message = [
			'branch_id.required' => 'Please select a branch',
			'role_id.required' => 'Please select a role',
			'branch_employee_id.required' => 'Please select a employee',
			'department_id.required' => 'Please select a department',
			'email.required' => 'Email field is required',
			'phone.required' => 'Phone Number is required',
			'address.required' => 'Address field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$branchEmployee = BranchEmployee::findOrFail($id);

		$branchEmployee->branch_id = $request->branch_id;
		$branchEmployee->role_id = $request->role_id;
		$branchEmployee->admin_id = $request->branch_employee_id;
		$branchEmployee->department_id = $request->department_id;
		$branchEmployee->email = $request->email;
		$branchEmployee->phone = $request->phone;
		$branchEmployee->address = $request->address;
		$branchEmployee->national_id = $request->national_id;
		$branchEmployee->status = $request->status;

		if ($request->hasFile('image')) {
			try {
				$image = $this->fileUpload($request->image, config('location.branchEmployee.path'));
				if ($image) {
					$branchEmployee->image = $image['path'] ?? null;
					$branchEmployee->driver = $image['driver'] ?? null;
				}
			} catch (\Exception $exp) {
				return back()->with('error', 'Image could not be uploaded.');
			}
		}

		$branchEmployee->save();
		return back()->with('success', 'Branch Employee Updated Successfully!');
	}


	public function branchDriverList(Request $request)
	{
		$search = $request->all();
		$authenticateUser = Auth::guard('admin')->user();

		$data['branchDrivers'] = BranchDriver::with('branch.branchManager', 'admin')
			->when(isset($authenticateUser->role_id), function ($query) use ($authenticateUser) {
				return $query->whereHas('branch.branchManager', function ($qry) use ($authenticateUser) {
					$qry->where(['admin_id' => $authenticateUser->id]);
				});
			})
			->when(isset($search['branch']), function ($query) use ($search) {
				return $query->whereHas('branch', function ($q) use ($search) {
					$q->whereRaw("branch_name REGEXP '[[:<:]]{$search['branch']}[[:>:]]'");
				});
			})
			->when(isset($search['phone']), function ($q) use ($search) {
				return $q->where('phone', $search['phone']);
			})
			->when(isset($search['email']), function ($q2) use ($search) {
				return $q2->where('email', $search['email']);
			})
			->when(isset($search['status']) && $search['status'] == 'active', function ($q3) use ($search) {
				return $q3->where('status', 1);
			})
			->when(isset($search['status']) && $search['status'] == 'deactive', function ($q4) use ($search) {
				return $q4->where('status', 0);
			})
			->paginate(config('basic.paginate'));
		return view('admin.branchDriver.index', $data, compact('authenticateUser'));
	}


	public function createDriver()
	{
		$authenticateUser = Auth::guard('admin')->user();
		$data['allBranches'] = Branch::with('branchManager')
			->when(isset($authenticateUser->role_id), function ($query) use ($authenticateUser) {
				return $query->whereHas('branchManager', function ($q) use ($authenticateUser) {
					$q->where('admin_id', $authenticateUser->id);
				});
			})
			->where('status', 1)->get();

		$data['allRoles'] = Role::where('status', 1)->get();
		return view('admin.branchDriver.create', $data, compact('authenticateUser'));
	}

	public function branchDriverStore(Request $request)
	{

		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));

		$rules = [
			'branch_id' => ['required', 'exists:branches,id'],
			'role_id' => ['required', 'exists:roles,id'],
			'branch_driver_id' => ['required', 'exists:admins,id'],
			'email' => ['required', 'email', 'nullable'],
			'phone' => ['required', 'numeric', 'nullable'],
			'address' => ['required', 'max:2000'],
			'national_id' => ['nullable', 'max:100'],
			'image' => ['nullable', 'max:3072', 'mimes:jpg,jpeg,png']
		];

		$message = [
			'branch_id.required' => 'Please select a branch',
			'role_id.required' => 'Please select a role',
			'branch_driver_id.required' => 'Please select a driver',
			'email.required' => 'Email field is required',
			'phone.required' => 'Phone Number is required',
			'address.required' => 'Address field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$branchDriver = new BranchDriver();

		$branchDriver->branch_id = $request->branch_id;
		$branchDriver->role_id = $request->role_id;
		$branchDriver->admin_id = $request->branch_driver_id;
		$branchDriver->email = $request->email;
		$branchDriver->phone = $request->phone;
		$branchDriver->address = $request->address;
		$branchDriver->national_id = $request->national_id;
		$branchDriver->status = $request->status;

		if ($request->hasFile('image')) {
			try {
				$image = $this->fileUpload($request->image, config('location.branchDriver.path'));
				if ($image) {
					$branchDriver->image = $image['path'] ?? null;
					$branchDriver->driver = $image['driver'] ?? null;
				}
			} catch (\Exception $exp) {
				return back()->with('error', 'Image could not be uploaded.');
			}
		}

		$branchDriver->save();
		return back()->with('success', 'Branch Driver Created Successfully!');
	}

	public function branchDriverEdit($id)
	{
		$authenticateUser = Auth::guard('admin')->user();
		$data['allBranches'] = Branch::with('branchManager')
			->when(isset($authenticateUser->role_id), function ($query) use ($authenticateUser) {
				return $query->whereHas('branchManager', function ($q) use ($authenticateUser) {
					$q->where('admin_id', $authenticateUser->id);
				});
			})
			->where('status', 1)->get();

		$data['allRoles'] = Role::where('status', 1)->get();
		$data['singleBranchDriverInfo'] = BranchDriver::with('branch', 'admin')->where('status', 1)
			->when(isset($authenticateUser->role_id), function ($query) use ($authenticateUser) {
				return $query->whereHas('branch.branchManager', function ($qry) use ($authenticateUser) {
					$qry->where(['admin_id' => $authenticateUser->id]);
				});
			})
			->findOrFail($id);

		$data['allDrivers'] = Admin::where('role_id', $data['singleBranchDriverInfo']->role_id)->where('status', 1)->get();

		return view('admin.branchDriver.edit', $data, compact('authenticateUser'));
	}

	public function branchDriverUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));

		$rules = [
			'branch_id' => ['required', 'exists:branches,id'],
			'role_id' => ['required', 'exists:roles,id'],
			'branch_driver_id' => ['required', 'exists:admins,id'],
			'email' => ['required', 'email', 'nullable'],
			'phone' => ['required', 'numeric', 'nullable'],
			'address' => ['required', 'max:2000'],
			'national_id' => ['nullable', 'max:100'],
			'image' => ['nullable', 'max:3072', 'mimes:jpg,jpeg,png']
		];

		$message = [
			'branch_id.required' => 'Please select a branch',
			'role_id.required' => 'Please select a role',
			'branch_driver_id.required' => 'Please select a driver',
			'email.required' => 'Email field is required',
			'phone.required' => 'Phone Number is required',
			'address.required' => 'Address field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$branchDriver = BranchDriver::findOrFail($id);

		$branchDriver->branch_id = $request->branch_id;
		$branchDriver->role_id = $request->role_id;
		$branchDriver->admin_id = $request->branch_driver_id;
		$branchDriver->email = $request->email;
		$branchDriver->phone = $request->phone;
		$branchDriver->address = $request->address;
		$branchDriver->national_id = $request->national_id;
		$branchDriver->status = $request->status;

		if ($request->hasFile('image')) {
			try {
				$image = $this->fileUpload($request->image, config('location.branchDriver.path'));
				if ($image) {
					$branchDriver->image = $image['path'] ?? null;
					$branchDriver->driver = $image['driver'] ?? null;
				}
			} catch (\Exception $exp) {
				return back()->with('error', 'Image could not be uploaded.');
			}
		}

		$branchDriver->save();
		return back()->with('success', 'Branch Driver Updated Successfully!');
	}

	public function branchStaffList($id)
	{
		$data['branchStaffList'] = BranchEmployee::with('branch', 'admin', 'department')
			->where('branch_id', $id)
			->get();
		return view('admin.branchEmployee.staffList', $data);
	}


	public function getRoleUser(Request $request)
	{
		$results = Admin::where('status', 1)->where('role_id', $request->id)->get();
		return response($results);
	}

	public function getRoleUserInfo(Request $request)
	{
		$results = Admin::where('status', 1)->where('id', $request->id)->first();
		return response($results);
	}

}
