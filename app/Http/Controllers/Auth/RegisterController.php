<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\UserSystemInfo;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Template;
use App\Models\UserProfile;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
	use RegistersUsers;

	public function showRegistrationForm(Request $request)
	{
		if(!basicControl()->registration){
			return redirect('/')->with('error','Registration has been disabled');
		}
		$referral = $request->referral;
		$info = json_decode(json_encode(getIpInfo()), true);
		$country_code = null;
		if (!empty($info['code'])) {
			$country_code = $info['code'][0];
		}
		$countries = config('country');
		$template = Template::where('section_name', 'register')->first();
		return view(template() . 'auth.register', compact('countries', 'referral', 'country_code', 'template'));
	}

	protected $redirectTo = RouteServiceProvider::HOME;

	public function __construct()
	{
		$this->middleware('guest');
	}

	protected function validator(array $data)
	{
		$validateData = [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
			'username' => ['required', 'string', 'max:50', 'unique:users,username'],
			'phone' => ['required', 'string', 'unique:user_profiles,phone'],
		];


		if(basicControl()->strong_password == 0){
			$validateData['password'] = ['required', 'min:6', 'confirmed'];
		}else{
			$validateData['password'] = ["required",'confirmed',
				Password::min(6)->mixedCase()
					->letters()
					->numbers()
					->symbols()
					->uncompromised()];
		}


		if (basicControl()->google_reCaptcha_status == 1 && basicControl()->reCaptcha_status_registration) {
			$validateData['g-recaptcha-response'] = 'required|captcha';
		}

		if (basicControl()->manual_reCaptcha_status == 1  && basicControl()->reCaptcha_status_registration) {
			$validateData['captcha'] = ['required',
				Rule::when((!empty($request->captcha) && strcasecmp(session()->get('captcha'), $_POST['captcha']) != 0), ['confirmed']),
			];
		}


		return Validator::make($data, $validateData,[
			'g-recaptcha-response.required' => 'The reCAPTCHA field is required.',
		]);
	}

	protected function create(array $data)
	{
		$ref_by = null;
		if (isset($data['referral'])) {
			$ref_by = User::where('username', $data['referral'])->first();
		}
		if (!isset($ref_by)) {
			$ref_by = null;
		}


		$ul['ip_address'] = UserSystemInfo::get_ip();
		$ul['browser'] = UserSystemInfo::get_browsers();
		$ul['os'] = UserSystemInfo::get_os();
		$ul['get_device'] = UserSystemInfo::get_device();

		$user = User::create([
			'name' => $data['name'],
			'ref_by' => $ref_by,
			'email' => $data['email'],
			'username' => $data['username'],
			'password' => Hash::make($data['password']),
			'language_id' => Language::select('id')->where('default_status', true)->first()->name ?? null,
			'email_verification' => (basicControl()->email_verification) ? 0 : 1,
			'sms_verification' => (basicControl()->sms_verification) ? 0 : 1,
			'browser_history' => $ul['browser'],
			'os_history' => $ul['os'],
			'device_history' => $ul['get_device'],
			'timezone' => $data['timezone'],
			'last_login' => Carbon::now()
		]);

		$userProfile = UserProfile::firstOrCreate(['user_id' => $user->id]);
		$userProfile->phone_code = $data['phone_code'];
		$userProfile->phone = $data['phone'];
		$userProfile->save();

		return $user;
	}

	protected function registered(Request $request, $user)
	{

		$user->two_fa_verify = ($user->two_fa == 1) ? 0 : 1;
		$user->save();

	}
}
