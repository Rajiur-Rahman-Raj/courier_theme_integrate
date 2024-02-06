<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipmentRequest;
use App\Models\BasicControl;
use App\Models\Branch;
use App\Models\Country;
use App\Models\DefaultShippingRateInternationally;
use App\Models\DefaultShippingRateOperatorCountry;
use App\Models\Package;
use App\Models\ParcelType;
use App\Models\Shipment;
use App\Models\User;
use App\Traits\Notify;
use App\Traits\OCShipmentStoreTrait;
use App\Traits\Upload;
use Facades\App\Services\NotifyMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserShipmentController extends Controller
{
	use Upload, Notify, OCShipmentStoreTrait;
	public function __construct()
	{
		$this->middleware(['auth']);
		$this->middleware(function ($request, $next) {
			$this->user = auth()->user();
			return $next($request);
		});
		$this->theme = template();
	}

    public function shipmentList(Request $request, $status = null, $type = null){
		$userShipmentManagement = config('userShipmentManagement');
		$types = array_keys($userShipmentManagement);
		abort_if(!in_array($type, $types), 404);
		$data['title'] = $userShipmentManagement[$type]['title'];

		$filterData = $this->_filter($request, $status, $type);
		$search = $filterData['search'];
		$userId = $filterData['userId'];
		$data['allShipments'] = $filterData['allShipments']
			->where('sender_id', $userId)
			->latest()
			->paginate(config('basic.paginate'));

		return view($this->theme . $userShipmentManagement[$type]['shipment_view'], $data, compact('type', 'search', 'status'));
	}


	public function _filter($request, $status, $type)
	{
		$userId = Auth::id();
		$search = $request->all();

		$allShipments = Shipment::with('senderBranch.branchManager', 'receiverBranch', 'sender', 'receiver', 'fromCountry', 'fromState', 'fromCity', 'fromArea', 'toCountry', 'toState', 'toCity', 'toArea')
			->when(isset($search['shipment_id']), function ($query) use ($search) {
				return $query->whereRaw("shipment_id REGEXP '[[:<:]]{$search['shipment_id']}[[:>:]]'");
			})
			->when(isset($search['shipment_type']), function ($query) use ($search) {
				return $query->where('shipment_type', $search['shipment_type']);
			})
			->when(isset($search['sender_branch']), function ($query) use ($search) {
				return $query->whereHas('senderBranch', function ($q) use ($search) {
					$q->whereRaw("branch_name REGEXP '[[:<:]]{$search['sender_branch']}[[:>:]]'");
				});
			})
			->when(isset($search['receiver_branch']), function ($query) use ($search) {
				return $query->whereHas('receiverBranch', function ($q) use ($search) {
					$q->whereRaw("branch_name REGEXP '[[:<:]]{$search['receiver_branch']}[[:>:]]'");
				});
			})
			->when(isset($search['shipment_date']), function ($query) use ($search) {
				$query->whereDate("shipment_date", $search['shipment_date']);
			})
			->when(isset($search['delivery_date']), function ($query) use ($search) {
				$query->whereDate("delivery_date", $search['delivery_date']);
			})
			->when($type == 'operator-country' && $status == 'all', function ($query) {
				$query->where('shipment_identifier', 1);
			})
			->when($type == 'operator-country' && $status == 'in_queue', function ($query) {
				$query->where('shipment_identifier', 1)
					->where('status', 1);
			})
			->when($type == 'operator-country' && $status == 'dispatch', function ($query) {
				$query->where('shipment_identifier', 1)
					->where('status', 2);
			})
			->when($type == 'operator-country' && $status == 'received', function ($query) {
				$query->where('shipment_identifier', 1)
					->where('status', 3);
			})
			->when($type == 'operator-country' && $status == 'delivered', function ($query) {
				$query->where('shipment_identifier', 1)
					->where('status', 4);
			})
			->when($type == 'operator-country' && $status == 'requested', function ($query) {
				$query->where('shipment_identifier', 1)
					->whereIn('status', [0,6]);
			})
			->when($type == 'internationally' && $status == 'all', function ($query) {
				$query->where('shipment_identifier', 2);
			})
			->when($type == 'internationally' && $status == 'in_queue', function ($query) {
				$query->where('shipment_identifier', 2)
					->where('status', 1);
			})
			->when($type == 'internationally' && $status == 'dispatch', function ($query) {
				$query->where('shipment_identifier', 2)
					->where('status', 2);
			})
			->when($type == 'internationally' && $status == 'received', function ($query) {
				$query->where('shipment_identifier', 2)
					->where('status', 3);
			})
			->when($type == 'internationally' && $status == 'delivered', function ($query) {
				$query->where('shipment_identifier', 2)
					->where('status', 4);
			})
			->when($type == 'internationally' && $status == 'requested', function ($query) {
				$query->where('shipment_identifier', 2)
					->whereIn('status', [0,6]);
			});

		$data = [
			'userId' => $userId,
			'search' => $search,
			'allShipments' => $allShipments,
		];

		return $data;
	}

	public function createShipment(Request $request, $type = null){
		$data['status'] = $request->input('shipment_status');
		$createShipmentType = ['operator-country', 'internationally'];
		abort_if(!in_array($type, $createShipmentType), 404);

		$data['shipmentTypeList'] = config('shipmentTypeList');

		$data['allBranches'] = Branch::where('status', 1)->get();
		$data['sender'] = Auth::user();

		$data['users'] = User::where('user_type', '!=', '0')->get();
		$data['senders'] = $data['users']->where('user_type', 1);
		$data['receivers'] = $data['users']->where('user_type', 2);
		$data['allCountries'] = Country::where('status', 1)->get();
		$data['packageList'] = Package::where('status', 1)->get();
		$data['parcelTypes'] = ParcelType::where('status', 1)->get();

		if ($type == 'operator-country') {
			$data['basicControl'] = BasicControl::with('operatorCountry')->first();
			$data['defaultShippingRateOC'] = DefaultShippingRateOperatorCountry::firstOrFail();
			return view($this->theme . 'user.shipments.operatorCountryShipmentCreate', $data);
		} elseif ($type == 'internationally') {
			$data['defaultShippingRateInternationally'] = DefaultShippingRateInternationally::first();
			return view($this->theme . 'user.shipments.internationallyShipmentCreate', $data);
		}
	}

	public function viewShipment(Request $request, $id){
		$user = Auth::user();
		$data['status'] = $request->input('segment');
		$data['shipment_type'] = $request->input('shipment_type');
		$data['singleShipment'] = Shipment::with('shipmentAttachments', 'senderBranch', 'receiverBranch', 'sender.profile', 'receiver', 'fromCountry', 'fromState', 'fromCity', 'fromArea', 'toCountry', 'toState', 'toCity', 'toArea')->where('sender_id', $user->id)->findOrFail($id);
		return view($this->theme . 'user.shipments.viewShipment', $data);
	}

	public function shipmentStore(ShipmentRequest $request, $type = null){
		try {
			DB::beginTransaction();
			$shipment = new Shipment();
			$fillData = $request->only($shipment->getFillable());
			$shipmentId = strRandom();
			$fillData['shipment_id'] = $shipmentId;

			if ($type == 'operator-country') {
				$fillData['shipment_identifier'] = 1;
				$fillData['receive_amount'] = $request->receive_amount != null ? $request->receive_amount : null;
			} elseif ($type == 'internationally') {
				$fillData['shipment_identifier'] = 2;
			}

			$fillData['from_city_id'] = $request->from_city_id ?? null;
			$fillData['to_city_id'] = $request->to_city_id ?? null;
			$fillData['from_area_id'] = $request->from_area_id ?? null;
			$fillData['to_area_id'] = $request->to_area_id ?? null;
			$fillData['shipment_by'] = 1;
			$fillData['status'] = 0; // shipment request

			if ($request->packing_service == 'yes') {
				$this->storePackingService($request, $shipment);
			} else {
				$fillData['packing_services'] = null;
			}

			if ($request->shipment_type == 'drop_off' || $request->shipment_type == 'pickup') {
				$this->storeParcelInformation($request, $shipment);
			} else {
				$fillData['parcel_information'] = null;
			}

			if ($request->shipment_type == 'condition') {
				$fillData['parcel_details'] = $request->parcel_details;
			}

			if($request->payment_type == 'cash'){
				$fillData['payment_status'] = 2;
			}elseif ($request->payment_type == 'wallet'){
				$fillData['payment_status'] = 1;
			}

			if ($request->payment_type == 'wallet') {
				$this->walletPaymentCalculation($request, $shipmentId);
			}

			$shipment->fill($fillData)->save();

			if ($request->hasFile('shipment_image')) {
				$getShipmentAttachments = $this->storeShipmentAttatchments($request, $shipment);
				if ($getShipmentAttachments['status'] == 'error') {
					throw new \Exception($getShipmentAttachments['message']);
				}
			}

			DB::commit();

			$sender = User::findOrFail($request->sender_id);
			NotifyMailService::customerSendShipmentRequest($shipment, $sender);

			return back()->with('success', 'Shipment request sent successfully!');

		} catch (\Exception $exp) {
			DB::rollBack();
			return back()->with('error', $exp->getMessage())->withInput();
		}
	}

	public function deleteShipmentRequest($id){
		$shipment = Shipment::findOrFail($id);
		$shipment->deleted_by = [0];
		$shipment->save();
		$shipment->delete();
		return back()->with('success', 'Shipment deleted successfully!');
	}

	public function cancelShipmentRequest($id){
		try {
			DB::beginTransaction();
			$basic = basicControl();
			$explodeData = explode('_', $basic->refund_time);
			$refund_time = $explodeData[0];
			$refund_time_type = strtolower($explodeData[1]);
			$func = $refund_time_type == 'minute' ? 'addMinutes' : ($refund_time_type == 'hour' ? 'addHours' : 'addDays');
			$moneyRefundTime = Carbon::now()->$func($refund_time);

			$shipment = Shipment::with('sender', 'receiver', 'senderBranch.branchManager.admin')->findOrFail($id);
			$shipment->status = 6;
			$shipment->shipment_cancel_time = Carbon::now();
			if ($shipment->payment_type == 'wallet' && $shipment->payment_status == 1){
				$shipment->refund_time = $moneyRefundTime;
			}

			$shipment->save();
			DB::commit();
			NotifyMailService::cancelShipmentRequestNotify($shipment, $refund_time, $refund_time_type);
			return back()->with('success', 'Shipment request canceled successfully!');
		}catch (\Exception $exp){
			DB::rollBack();
			return back()->with('error', $exp->getMessage())->withInput();
		}
	}
}
