<?php

namespace App\Http\Controllers;

use App\Models\IdentifyForm;
use App\Models\KYC;
use App\Models\Language;
use App\Models\UserProfile;
use App\Models\UserSocial;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
	use Upload;

	public function __construct()
	{
		$this->middleware(['auth']);
		$this->middleware(function ($request, $next) {
			$this->user = auth()->user();
			return $next($request);
		});
		$this->theme = template();
	}

	public function changePassword(Request $request)
	{
		if ($request->isMethod('get')) {
			return view($this->theme . 'user.profile.change');
		} elseif ($request->isMethod('post')) {
			$purifiedData = Purify::clean($request->all());
			$validator = Validator::make($purifiedData, [
				'currentPassword' => 'required|min:5',
				'password' => 'required|min:8|confirmed',
			]);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
			$user = Auth::user();
			$purifiedData = (object)$purifiedData;

			if (!Hash::check($purifiedData->currentPassword, $user->password)) {
				return back()->withInput()->withErrors(['currentPassword' => 'current password did not match']);
			}

			$user->password = bcrypt($purifiedData->password);
			$user->save();
			return back()->with('success', 'Password changed successfully');
		}
	}

	public function index(Request $request)
	{

		$validator = Validator::make($request->all(), []);
		$data['user'] = Auth::user();
		$data['userProfile'] = UserProfile::with('user')->firstOrCreate(['user_id' => $data['user']->id]);
		$data['countries'] = config('country');
		$data['languages'] = Language::select('id', 'name')->where('is_active', true)->orderBy('name', 'ASC')->get();

		return view($this->theme . 'user.profile.show', $data);
	}

	public function updateInformation(Request $request)
	{
		$languages = Language::all()->map(function ($item) {
			return $item->id;
		});

		$req = Purify::clean($request->all());
		$user = $this->user;
		$userProfile = UserProfile::firstOrCreate(['user_id' => $user->id]);
		$rules = [
			'name' => 'required',
			'username' => "sometimes|required|alpha_dash|min:5|unique:users,username," . $user->id,
		];
		$message = [
			'name.required' => 'Name field is required',
		];

		$validator = Validator::make($req, $rules, $message);
		if ($validator->fails()) {
			$validator->errors()->add('profile', '1');
			return back()->withErrors($validator)->withInput();
		}

		$user->name = $req['name'];
		$user->username = $req['username'];
		$user->email = $req['email'];
		$userProfile->phone = $req['phone'];
		$userProfile->address = $req['address'];
		$user->save();
		$userProfile->save();

		return back()->with('success', 'Profile Information Updated Successfully.');
	}

	public function updatePassword(Request $request)
	{
		$rules = [
			'current_password' => "required",
			'password' => "required|min:5|confirmed",
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			$validator->errors()->add('password', '1');
			return back()->withErrors($validator)->withInput();
		}
		$user = $this->user;
		try {
			if (Hash::check($request->current_password, $user->password)) {
				$user->password = bcrypt($request->password);
				$user->save();
				return back()->with('success', 'Password Update Successfully.');
			} else {
				throw new \Exception('Current password did not match');
			}
		} catch (\Exception $e) {
			return back()->with('error', $e->getMessage());
		}
	}

	public function verificationSubmit(Request $request)
	{
		$identityFormList = IdentifyForm::where('status', 1)->get();
		$rules['identity_type'] = ["required", Rule::in($identityFormList->pluck('slug')->toArray())];
		$identity_type = $request->identity_type;
		$identityForm = IdentifyForm::where('slug', trim($identity_type))->where('status', 1)->firstOrFail();

		$params = $identityForm->services_form;

		$rules = [];
		$inputField = [];
		$verifyImages = [];

		if ($params != null) {
			foreach ($params as $key => $cus) {
				$rules[$key] = [$cus->validation];
				if ($cus->type == 'file') {
					array_push($rules[$key], 'image');
					array_push($rules[$key], 'mimes:jpeg,jpg,png');
					array_push($rules[$key], 'max:2048');
					array_push($verifyImages, $key);
				}
				if ($cus->type == 'text') {
					array_push($rules[$key], 'max:191');
				}
				if ($cus->type == 'textarea') {
					array_push($rules[$key], 'max:300');
				}
				$inputField[] = $key;
			}
		}

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			$validator->errors()->add('identity', '1');

			return back()->withErrors($validator)->withInput();
		}


		$path = config('location.kyc.path') . date('Y') . '/' . date('m') . '/' . date('d');
		$collection = collect($request);

		$reqField = [];
		if ($params != null) {
			foreach ($collection as $k => $v) {
				foreach ($params as $inKey => $inVal) {
					if ($k != $inKey) {
						continue;
					} else {
						if ($inVal->type == 'file') {
							if ($request->hasFile($inKey)) {
								try {
									$reqField[$inKey] = [
										'field_name' => $this->uploadImage($request[$inKey], $path),
										'type' => $inVal->type,
									];
								} catch (\Exception $exp) {
									session()->flash('error', 'Could not upload your ' . $inKey);
									return back()->withInput();
								}
							}
						} else {
							$reqField[$inKey] = [
								'field_name' => $v,
								'type' => $inVal->type,
							];
						}
					}
				}
			}
		}

		try {

			DB::beginTransaction();

			$user = $this->user;
			$kyc = new KYC();
			$kyc->user_id = $user->id;
			$kyc->kyc_type = $identityForm->slug;
			$kyc->details = $reqField;
			$kyc->save();

			$user->identity_verify = 1;
			$user->save();

			if (!$kyc) {
				DB::rollBack();
				$validator->errors()->add('identity', '1');
				return back()->withErrors($validator)->withInput()->with('error', "Failed to submit request");
			}
			DB::commit();

			return redirect()->route('user.profile')->withErrors($validator)->with('success', 'KYC request has been submitted.');

		} catch (\Exception $e) {
			return redirect()->route('user.profile')->withErrors($validator)->with('error', $e->getMessage());
		}
	}


	public function addressVerification(Request $request)
	{
		$rules = [];
		$rules['addressProof'] = ['image', 'mimes:jpeg,jpg,png', 'max:2048'];
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			$validator->errors()->add('addressVerification', '1');
			return back()->withErrors($validator)->withInput();
		}

		$path = config('location.kyc.path') . date('Y') . '/' . date('m') . '/' . date('d');

		$reqField = [];
		try {
			if ($request->hasFile('addressProof')) {
				$reqField['addressProof'] = [
					'field_name' => $this->uploadImage($request['addressProof'], $path),
					'type' => 'file',
				];
			} else {
				$validator->errors()->add('addressVerification', '1');

				session()->flash('error', 'Please select a ' . 'address Proof');
				return back()->withInput();
			}
		} catch (\Exception $exp) {
			session()->flash('error', 'Could not upload your ' . 'address Proof');
			return redirect()->route('user.profile')->withInput();
		}

		try {

			DB::beginTransaction();
			$user = $this->user;
			$kyc = new KYC();
			$kyc->user_id = $user->id;
			$kyc->kyc_type = 'address-verification';
			$kyc->details = $reqField;
			$kyc->save();
			$user->address_verify = 1;
			$user->save();

			if (!$kyc) {
				DB::rollBack();
				$validator->errors()->add('addressVerification', '1');
				return redirect()->route('user.profile')->withErrors($validator)->withInput()->with('error', "Failed to submit request");
			}
			DB::commit();

			return redirect()->route('user.profile')->withErrors($validator)->with('success', 'Your request has been submitted.');

		} catch (\Exception $e) {
			$validator->errors()->add('addressVerification', '1');
			return redirect()->route('user.profile')->with('error', $e->getMessage())->withErrors($validator);
		}
	}

	public function updateProfile(Request $request)
	{
		$user = Auth::user();
		$userProfile = UserProfile::firstOrCreate(['user_id' => $user->id]);
		$allowedExtensions = array('jpg', 'png', 'jpeg');

		$image = $request->profile_picture;

		$this->validate($request, [
			'profile_picture' => [
				'required',
				'max:4096',
				function ($fail) use ($image, $allowedExtensions) {
					$ext = strtolower($image->getClientOriginalExtension());
					if (($image->getSize() / 1000000) > 2) {
						throw ValidationException::withMessages(['image' => 'Images MAX  2MB ALLOW!']);
					}
					if (!in_array($ext, $allowedExtensions)) {
						throw ValidationException::withMessages(['image' => 'Only png, jpg, jpeg images are allowed']);
					}
				}
			]
		]);


		if ($request->file('profile_picture') && $request->file('profile_picture')->isValid()) {
			$extension = $request->profile_picture->extension();
			$profileName = strtolower($user->username . '.' . $extension);
			$image = $this->fileUpload($request->profile_picture, config('location.user.path'), $userProfile->driver, $profileName, $userProfile->profile_picture);
			if ($image) {
				$userProfile->profile_picture = $image['path'];
				$userProfile->driver = $image['driver'];
			}
		}

		$userProfile->save();

		return back()->with('success', 'Updated Successfully.');
	}

}
