<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Models\UserProfile;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class ClientController extends Controller
{
	use Upload, Notify;

	public function clientList(Request $request)
	{
		$search = $request->all();
		$authenticateUser = Auth::guard('admin')->user();

		$data['clients'] = User::with('profile.branch.branchManager')->whereIn('user_type', [1, 2])
			->when(isset($authenticateUser->role_id), function ($query) use ($authenticateUser) {
				return $query->whereHas('profile.branch.branchManager', function ($qry) use ($authenticateUser) {
					$qry->where(['admin_id' => $authenticateUser->id]);
				});
			})
			->when(isset($search['name']), function ($query) use ($search) {
				return $query->whereRaw("name REGEXP '[[:<:]]{$search['name']}[[:>:]]'");
			})
			->when(isset($search['branch']), function ($query) use ($search) {
				return $query->whereHas('profile.branch', function ($q) use ($search) {
					$q->whereRaw("branch_name REGEXP '[[:<:]]{$search['branch']}[[:>:]]'");
				});
			})
			->when(isset($search['created_at']), function ($query) use ($search) {
				return $query->whereDate("created_at", $search['created_at']);
			})
			->when(isset($search['client_type']) && $search['client_type'] == 1, function ($q3) use ($search) {
				return $q3->where('user_type', 1);
			})
			->when(isset($search['client_type']) && $search['client_type'] == '2', function ($q4) use ($search) {
				return $q4->where('user_type', 2);
			})
			->when(isset($search['status']) && $search['status'] == 'active', function ($q3) use ($search) {
				return $q3->where('status', 1);
			})
			->when(isset($search['status']) && $search['status'] == 'deactive', function ($q4) use ($search) {
				return $q4->where('status', 0);
			})
			->paginate(config('basic.paginate'));
		return view('admin.clients.index', $data, compact('authenticateUser'));
	}


	public function createClient()
	{
		$authenticateUser = Auth::guard('admin')->user();
		$data['allBranches'] = Branch::with('branchManager')
			->where('status', 1)->get();
		$data['allCountires'] = Country::where('status', 1)->get();
		return view('admin.clients.create', $data, compact('authenticateUser'));
	}

	public function clientStore(Request $request)
	{

		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));

		$rules = [
			'name' => ['required', 'string', 'max:255'],
			'username' => ['required', 'string', 'max:50', 'unique:users,username'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
			'phone' => ['required', 'string', 'unique:user_profiles,phone'],
			'national_id' => ['nullable', 'max:100', 'min:6'],
			'password' => ['required', 'string', 'min:6'],
			'branch_id' => ['required', 'exists:branches,id'],
			'country_id' => ['required', 'exists:countries,id'],
			'state_id' => ['required', 'exists:states,id'],
			'city_id' => ['nullable', 'exists:cities,id'],
			'area_id' => ['nullable', 'exists:areas,id'],
			'address' => ['required', 'max:1000'],
			'image' => ['nullable', 'max:3072', 'mimes:jpg,jpeg,png']
		];

		$message = [
			'name.required' => 'Name field is required',
			'username.required' => 'User name field is required',
			'email.required' => 'Email field is required',
			'phone.required' => 'Phone Number is required',
			'password.required' => 'Password field is required',
			'branch_id.required' => 'Please select a branch',
			'country_id.required' => 'Please select a country',
			'state_id.required' => 'Please select a state'
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$user = new User();

		$user->name = $request->name;
		$user->username = $request->username;
		$user->email = $request->email;
		$user->password = Hash::make($request->password);
		$user->user_type = (int)$request->client_type;
		$user->status = $request->status;

		$user->save();

		$userProfile = UserProfile::firstOrCreate(['user_id' => $user->id]);
		$userProfile->phone = $request->phone;
		$userProfile->address = $request->address;
		$userProfile->national_id = $request->national_id;
		$userProfile->branch_id = $request->branch_id;
		$userProfile->country_id = $request->country_id;
		$userProfile->state_id = $request->state_id;
		$userProfile->city_id = $request->city_id;
		$userProfile->area_id = $request->area_id;

		if ($request->hasFile('image')) {
			try {
				$image = $this->fileUpload($request->image, config('location.user.path'));
				if ($image) {
					$userProfile->profile_picture = $image['path'] ?? null;
					$userProfile->driver = $image['driver'] ?? null;
				}
			} catch (\Exception $exp) {
				return back()->with('error', 'Image could not be uploaded.');
			}
		}

		$userProfile->save();

		return back()->with('success', 'Client Created Successfully!');
	}

	public function clientEdit($id)
	{
		$authenticateUser = Auth::guard('admin')->user();
		$data['allBranches'] = Branch::where('status', 1)->get();
		$data['allCountries'] = Country::where('status', 1)->get();
		$data['allStates'] = State::where('status', 1)->get();
		$data['allCities'] = City::where('status', 1)->get();
		$data['allAreas'] = Area::where('status', 1)->get();
		$data['singleClientInfo'] = User::with('profile', 'profile.branch.branchManager', 'profile.country', 'profile.state', 'profile.city', 'profile.area')
			->when(isset($authenticateUser->role_id), function ($query) use ($authenticateUser) {
				return $query->whereHas('profile.branch.branchManager', function ($qry) use ($authenticateUser) {
					$qry->where(['admin_id' => $authenticateUser->id]);
				});
			})
			->findOrFail($id);
		return view('admin.clients.edit', $data);
	}

	public function clientUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));

		$rules = [
			'name' => ['required', 'string', 'max:255'],
			'username' => ['required', 'string', 'max:50'],
			'email' => ['required', 'string', 'email', 'max:255'],
			'phone' => ['required', 'string'],
			'national_id' => ['nullable', 'max:100', 'min:6'],
			'password' => ['nullable', 'min:6', 'max:50'],
			'branch_id' => ['required', 'exists:branches,id'],
			'country_id' => ['required', 'exists:countries,id'],
			'state_id' => ['required', 'exists:states,id'],
			'city_id' => ['nullable', 'exists:cities,id'],
			'area_id' => ['nullable', 'exists:areas,id'],
			'address' => ['required', 'max:1000'],
			'image' => ['nullable', 'max:3072', 'mimes:jpg,jpeg,png']
		];

		$message = [
			'name.required' => 'Name field is required',
			'username.required' => 'User name field is required',
			'email.required' => 'Email field is required',
			'phone.required' => 'Phone Number is required',
			'branch_id.required' => 'Please select a branch',
			'country_id.required' => 'Please select a country',
			'state_id.required' => 'Please select a state'
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$user = User::findOrFail($id);


		$user->name = $request->name;
		$user->username = $request->username;
		$user->email = $request->email;
		$user->password = $request->password == null ? $user->password : Hash::make($request->password);
		$user->user_type = (int)$request->client_type;
		$user->status = $request->status;

		$user->save();

		$userProfile = UserProfile::firstOrCreate(['user_id' => $user->id]);
		$userProfile->phone = $request->phone;
		$userProfile->address = $request->address;
		$userProfile->national_id = $request->national_id;
		$userProfile->branch_id = $request->branch_id;
		$userProfile->country_id = $request->country_id;
		$userProfile->state_id = $request->state_id;
		$userProfile->city_id = $request->city_id;
		$userProfile->area_id = $request->area_id;

		if ($request->hasFile('image')) {
			try {
				$image = $this->fileUpload($request->image, config('location.user.path'));
				if ($image) {
					$userProfile->profile_picture = $image['path'] ?? null;
					$userProfile->driver = $image['driver'] ?? null;
				}
			} catch (\Exception $exp) {
				return back()->with('error', 'Image could not be uploaded.');
			}
		}

		$userProfile->save();

		return back()->with('success', 'Client Updated Successfully!');
	}

}
