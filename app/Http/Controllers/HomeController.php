<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Country;
use App\Models\FirebaseNotify;
use App\Models\MoneyTransfer;
use App\Models\Shipment;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use DateTime;
use App\Models\Fund;
use App\Traits\Upload;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HomeController extends Controller
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

	public function getTransactionChart(Request $request)
	{
		$start = $request->start;
		$end = $request->end;
		$user = Auth::user();

		$transactions = Transaction::select('created_at')
			->whereBetween('created_at', [$start, $end])
			->with(['transactional' => function (MorphTo $morphTo) {
				$morphTo->morphWith([
					Fund::class => ['sender', 'receiver', 'currency'],
				]);
			}])
			->whereHasMorph('transactional',
				[
					Fund::class,
				], function ($query, $type) use ($user) {
					if ($type === Fund::class) {
						$query->where('user_id', $user->id);
					}
				})
			->groupBy([DB::raw("DATE_FORMAT(created_at, '%j')")])
			->selectRaw("SUM(CASE WHEN transactional_type like '%Deposit' THEN amount ELSE 0 END) as Deposit")
			->selectRaw("SUM(CASE WHEN transactional_type like '%Fund' THEN amount ELSE 0 END) as Fund")
			->selectRaw("SUM(CASE WHEN transactional_type like '%Payout' THEN amount ELSE 0 END) as Payout")
			->get()
			->groupBy([function ($query) {
				return $query->created_at->format('j');
			}]);

		$labels = [];
		$dataDeposit = [];
		$dataFund = [];
		$dataPayout = [];
		$start = new DateTime($start);
		$end = new DateTime($end);

		for ($day = $start; $day <= $end; $day->modify('+1 day')) {
			$i = $day->format('j');
			$labels[] = $day->format('jS M');
			$currentDeposit = 0;
			$currentFund = 0;
			$currentPayout = 0;
			if (isset($transactions[$i])) {
				foreach ($transactions[$i] as $key => $transaction) {
					$currentDeposit += $transaction->Deposit;
					$currentFund += $transaction->Fund;
					$currentPayout += $transaction->Payout;
				}
			}
			$dataDeposit[] = round($currentDeposit, basicControl()->fraction_number);
			$dataFund[] = round($currentFund, basicControl()->fraction_number);
			$dataPayout[] = round($currentPayout, basicControl()->fraction_number);
		}

		$data['labels'] = $labels;
		$data['dataDeposit'] = $dataDeposit;
		$data['dataFund'] = $dataFund;
		$data['dataPayout'] = $dataPayout;


		return response()->json($data);
	}

	public function index()
	{
		$basic = basicControl();
		$fraction = $basic->fraction_number;
		$user = Auth::user();
		$last30 = date('Y-m-d', strtotime('-30 days'));
		$last7 = date('Y-m-d', strtotime('-7 days'));
		$today = today();
		$dayCount = date('t', strtotime($today));

		$data['walletBalance'] = getAmount($this->user->balance);
		$data['totalDeposit'] = getAmount($this->user->funds()->whereStatus(1)->sum('amount'));

		$shipments = Shipment::selectRaw('COUNT(shipments.id) AS totalShipments')
			->selectRaw('COUNT(CASE WHEN shipments.shipment_identifier = 1 THEN shipments.id END) AS totalOperatorCountryShipments')
			->selectRaw('COUNT(CASE WHEN shipments.shipment_identifier = 2 THEN shipments.id END) AS totalInternationallyShipments')
			->selectRaw('COUNT(CASE WHEN shipments.status = 0 THEN shipments.id END) AS totalPendingShipments')
			->selectRaw('COUNT(CASE WHEN shipments.status = 1 THEN shipments.id END) AS totalInQueueShipments')
			->selectRaw('COUNT(CASE WHEN shipments.status = 4 THEN shipments.id END) AS totalDeliveredShipments')
			->selectRaw('COUNT(CASE WHEN shipments.status = 11 THEN shipments.id END) AS totalReturnShipments')
			->where('sender_id', $user->id)
			->get()
			->toArray();

		$data['shipmentRecord'] = collect($shipments)->collapse();


		$shipmentTransactions = Transaction::selectRaw('SUM(CASE WHEN shipment_id IS NOT NULL THEN amount ELSE 0 END) AS totalShipmentTransactions')
			->where('user_id', $user->id)
			->get()
			->toArray();

		$data['transactionRecord'] = collect($shipmentTransactions)->collapse();

		$transactions = Transaction::select('created_at')
			->whereMonth('created_at', $today)
			->with(['transactional' => function (MorphTo $morphTo) {
				$morphTo->morphWith([
					Fund::class => ['sender', 'receiver'],
				]);
			}])
			->whereHasMorph('transactional',
				[
					Fund::class
				],
				function ($query, $type) use ($user) {
					if ($type === Fund::class) {
						$query->where('user_id', $user->id);
					}
				})
			->groupBy([DB::raw("DATE_FORMAT(created_at, '%j')")])
			->selectRaw("SUM(CASE WHEN transactional_type like '%Deposit' THEN amount ELSE 0 END) as Deposit")
			->selectRaw("SUM(CASE WHEN transactional_type like '%Fund' THEN amount ELSE 0 END) as Fund")
			->selectRaw("SUM(CASE WHEN transactional_type like '%Payout' THEN amount ELSE 0 END) as Payout")
			->get()
			->groupBy([function ($query) {
				return $query->created_at->format('j');
			}]);

		$labels = [];
		$dataDeposit = [];
		$dataFund = [];
		$dataPayout = [];
		for ($i = 1; $i <= $dayCount; $i++) {
			$labels[] = date('jS M', strtotime(date('Y/m/') . $i));
			$currentDeposit = 0;
			$currentFund = 0;
			$currentPayout = 0;
			if (isset($transactions[$i])) {
				foreach ($transactions[$i] as $key => $transaction) {
					$currentDeposit += $transaction->Deposit;
					$currentFund += $transaction->Fund;
					$currentPayout += $transaction->Payout;
				}
			}
			$dataDeposit[] = round($currentDeposit, $fraction);
			$dataFund[] = round($currentFund, $fraction);
			$dataPayout[] = round($currentPayout, $fraction);
		}

		$data['basic'] = $basic;
		$data['labels'] = $labels;
		$data['dataDeposit'] = $dataDeposit;
		$data['dataFund'] = $dataFund;
		$data['dataPayout'] = $dataPayout;

		$data['allShipments'] = Shipment::with('senderBranch.branchManager', 'receiverBranch', 'sender', 'receiver', 'fromCountry', 'fromState', 'fromCity', 'fromArea', 'toCountry', 'toState', 'toCity', 'toArea')
			->where('sender_id', $user->id)
			->latest()
			->limit(5)
			->get();

		$data['firebaseNotify'] = FirebaseNotify::first();

		return view($this->theme . 'user.home', $data);
	}


	public function logoUpdate(Request $request)
	{
		if ($request->isMethod('get')) {
			return view('admin.control_panel.logo');
		} elseif ($request->isMethod('post')) {

			if ($request->hasFile('logo')) {
				try {
					$old = 'logo.png';
					$image = $this->fileUpload($request->logo, config('location.logo.path'), config('basic.default_file_driver'), $old, $old);
					if ($image) {
						config(['basic.logo_image' => $image['path']]);
					}
				} catch (\Exception $exp) {
					return back()->with('error', 'Logo could not be uploaded.');
				}
			}
			if ($request->hasFile('footer_logo')) {
				try {
					$old = 'footer-logo.png';
					$image = $this->fileUpload($request->footer_logo, config('location.logo.path'), config('basic.default_file_driver'), $old, $old);
					if ($image) {
						config(['basic.footer_image' => $image['path']]);
					}
				} catch (\Exception $exp) {
					return back()->with('error', 'Footer Logo could not be uploaded.');
				}
			}
			if ($request->hasFile('admin_logo')) {
				try {
					$old = 'admin-logo.png';
					$image = $this->fileUpload($request->admin_logo, config('location.logo.path'), config('basic.default_file_driver'), $old, $old);
					if ($image) {
						config(['basic.admin_logo' => $image['path']]);
					}
				} catch (\Exception $exp) {
					return back()->with('error', 'Logo could not be uploaded.');
				}
			}
			if ($request->hasFile('favicon')) {
				try {
					$old = 'favicon.png';
					$image = $this->fileUpload($request->favicon, config('location.logo.path'), config('basic.default_file_driver'), $old, $old);
					if ($image) {
						config(['basic.favicon_image' => $image['path']]);
					}
				} catch (\Exception $exp) {
					return back()->with('error', 'Favicon could not be uploaded.');
				}
			}

			if ($request->hasFile('breadcrumb')) {
				try {
					$old = 'breadcrumb.png';
					$this->uploadImage($request->breadcrumb, config('location.breadcrumb.path'), config('location.breadcrumb.size'), $old, $old);
				} catch (\Exception $exp) {
					return back()->with('error', 'Breadcrumb could not be uploaded.');
				}
			}

			$fp = fopen(base_path() . '/config/basic.php', 'w');
			fwrite($fp, '<?php return ' . var_export(config('basic'), true) . ';');
			fclose($fp);

			return back()->with('success', 'Logo, favicon and breadcrumb has been updated.');
		}
	}


	public function seoUpdate(Request $request)
	{
		$basicControl = basicControl();
		if ($request->isMethod('get')) {
			return view('admin.control_panel.seo', compact('basicControl'));
		} elseif ($request->isMethod('post')) {

			$purifiedData = Purify::clean($request->all());
			$purifiedData['image'] = $request->image;
			$validator = Validator::make($purifiedData, [
				'meta_keywords' => 'nullable|min:1',
				'meta_description' => 'nullable|string|min:1',
				'social_title' => 'nullable|string|min:1',
				'social_description' => 'nullable|string|min:1',
				'image' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
			]);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			$purifiedData = (object)$purifiedData;
			$basicControl->meta_keywords = collect($request->meta_keywords)->unique()->filter(function ($item) {
				if (!in_array($item, ['', null])) {
					return $item;
				}
			})->join(',');
			$basicControl->meta_description = $purifiedData->meta_description;
			$basicControl->social_title = $purifiedData->social_title;
			$basicControl->social_description = $purifiedData->social_description;
			$basicControl->save();

			if ($request->hasFile('image')) {
				try {
					$old = 'meta.png';
					$this->uploadImage($request->image, config('location.logo.path'), null, $old, $old);
				} catch (\Exception $exp) {
					return back()->with('error', 'Meta image could not be uploaded.');
				}
			}

			return back()->with('success', 'Seo has been updated.');
		}
	}

	public function moneyTransfer()
	{
		$page_title = "Balance Transfer";
		return view($this->theme . 'user.money-transfer', compact('page_title'));
	}

	public function moneyTransferConfirm(Request $request)
	{

		$this->validate($request, [
			'email' => 'required|email',
			'amount' => 'required',
			'password' => 'required',
			'balance'  => 'required'
		]);

		$basic = (object)config('basic');
		$email = trim($request->email);


		$receiver = User::where('email', $email)->first();

		if (!$receiver) {
			session()->flash('error', 'This email could not Found!');
			return back()->withInput();
		}

		if ($receiver->id == Auth::id()) {
			session()->flash('error', 'Money cannot be sent from own wallet to own wallet!');
			return back()->withInput();
		}

		if ($receiver->status == 0) {
			session()->flash('error', 'Invalid User!');
			return back()->withInput();
		}

		if ($request->amount < $basic->min_transfer) {
			session()->flash('error', 'Minimum Transfer Amount ' . $basic->min_transfer . ' ' . $basic->base_currency);
			return back()->withInput();
		}

		if ($request->amount > $basic->max_transfer) {
			session()->flash('error', 'Maximum Transfer Amount ' . $basic->max_transfer . ' ' . $basic->base_currency);
			return back()->withInput();
		}

		$transferCharge = ($request->amount * $basic->transfer_charge) / 100;

		$user = Auth::user();

		if ($user->balance >= ($request->amount + $transferCharge)) {

			if (Hash::check($request->password, $user->password)) {

				$sendMoneyCheck = MoneyTransfer::where('sender_id', $user->id)->where('receiver_id', $receiver->id)->latest()->first();

				if (isset($sendMoneyCheck) && Carbon::parse($sendMoneyCheck->send_at) > Carbon::now()) {

					$time = $sendMoneyCheck->send_at;
					$delay = $time->diffInSeconds(Carbon::now());
					$delay = gmdate('i:s', $delay);

					session()->flash('error', 'You can send money to this user after  delay ' . $delay . ' minutes');
					return back()->withInput();
				} else {

					$user->balance = round(($user->balance - ($transferCharge + $request->amount)), 2);
					$user->save();

					$receiver->balance += round($request->amount, 2);
					$receiver->save();

					$trans = strRandom();

					$sendTaka = new MoneyTransfer();
					$sendTaka->sender_id = $user->id;
					$sendTaka->receiver_id = $receiver->id;
					$sendTaka->amount = round($request->amount, 2);
					$sendTaka->charge = $transferCharge;
					$sendTaka->trx = $trans;
					$sendTaka->send_at = Carbon::parse()->addMinutes(1);
					$sendTaka->save();

					$transaction = new Transaction();
					$transaction->user_id = $user->id;
					$transaction->transactional_id = null;
					$transaction->amount = round($request->amount, 2);
					$transaction->charge = $transferCharge;
					$transaction->final_balance = $user->balance;
					$transaction->trx_type = '-';
					$transaction->trx_id = $trans;
					$transaction->remarks = 'Balance Transfer to  ' . $receiver->email;
					$transaction->transactional_type = MoneyTransfer::class;
					$sendTaka->transactional()->save($transaction);

					$transaction = new Transaction();
					$transaction->user_id = $receiver->id;
					$transaction->transactional_id = null;
					$transaction->amount = round($request->amount, 2);
					$transaction->charge = 0;
					$transaction->final_balance = $receiver->balance;
					$transaction->trx_type = '+';
					$transaction->trx_id = $trans;
					$transaction->remarks = 'Balance Transfer From  ' . $user->email;
					$sendTaka->transactional()->save($transaction);

					return back()->with('success', 'Balance transfer has been successfull');
				}
			} else {
				session()->flash('error', 'Password Do Not Match!');
				return back()->withInput();
			}
		} else {
			session()->flash('error', 'Insufficient Balance!');
			return back()->withInput();
		}
	}

	public function receiverList(){
		$data['allReceivers'] = User::with('profile.branch')->where('status', 1)->where('created_by', Auth::id())->latest()->paginate(config('basic.paginate'));
		return view($this->theme . 'user.receiver.list', $data);
	}

	public function receiverCreate(){
		$data['allBranches'] = Branch::where('status', 1)->get();
		$data['allCountries'] = Country::where('status', 1)->get();
		return  view($this->theme . 'user.receiver.create', $data);
	}

	public function receiverStore(Request $request){
		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));
		$rules = [
			'name' => ['required', 'string', 'max:255'],
			'username' => ['required', 'string', 'max:50', 'unique:users,username'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
			'phone' => ['required', 'string', 'unique:user_profiles,phone'],
			'password' => ['required', 'string', 'min:6'],
			'branch_id' => ['required', 'exists:branches,id'],
			'country_id' => ['nullable', 'exists:countries,id'],
			'state_id' => ['nullable', 'exists:states,id'],
			'city_id' => ['nullable', 'exists:cities,id'],
			'area_id' => ['nullable', 'exists:areas,id'],
			'address' => ['required', 'max:1000'],
		];

		$message = [
			'name.required' => 'Name field is required',
			'username.required' => 'User name field is required',
			'email.required' => 'Email field is required',
			'phone.required' => 'Phone Number is required',
			'password.required' => 'Password field is required',
			'branch_id.required' => 'Please select a branch',
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
		$user->user_type = 2;
		$user->status = 1;
		$user->created_by = Auth::id();

		$user->save();

		$userProfile = UserProfile::firstOrCreate(['user_id' => $user->id]);
		$userProfile->phone = $request->phone;
		$userProfile->address = $request->address;
		$userProfile->branch_id = $request->branch_id;
		$userProfile->country_id = $request->country_id;
		$userProfile->state_id = $request->state_id;
		$userProfile->city_id = $request->city_id;
		$userProfile->area_id = $request->area_id;

		$userProfile->save();

		return back()->with('success', 'Receiver Created Successfully!');
	}

	public function saveToken(Request $request)
	{
		$user = auth()->user();
		$user->fcm_token = $request->token;
		$user->save();
		return response()->json(['token saved successfully.']);
	}

}
