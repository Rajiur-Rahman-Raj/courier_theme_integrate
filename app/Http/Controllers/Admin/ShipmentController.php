<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ShipmentReportCountExport;
use App\Exports\ShipmentReportExport;
use App\Exports\ShipmentTransactionReportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShipmentRequest;
use App\Models\Admin;
use App\Models\Shipment;
use App\Models\ShipmentAttatchment;
use App\Models\Transaction;
use Facades\App\Services\TransactionService;
use App\Traits\OCShipmentStoreTrait;
use App\Traits\ShipmentUpdateTrait;
use App\Traits\Notify;
use Facades\App\Services\NotifyMailService;
use App\Traits\Upload;
use App\Models\BasicControl;
use App\Models\Branch;
use App\Models\Country;
use App\Models\DefaultShippingRateInternationally;
use App\Models\DefaultShippingRateOperatorCountry;
use App\Models\OCSAttatchment;
use App\Models\OperatorCountryShipment;
use App\Models\Package;
use App\Models\ParcelType;
use App\Models\ShippingDate;
use App\Models\ShippingRateInternationally;
use App\Models\ShippingRateOperatorCountry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Stevebauman\Purify\Facades\Purify;

class ShipmentController extends Controller
{
	use Upload, Notify, OCShipmentStoreTrait, ShipmentUpdateTrait;


	public function shipmentReport(Request $request)
	{
		$data['authenticateUser'] = Auth::guard('admin')->user();
		$data['branchId'] = null;
		if (isset($data['authenticateUser']->branch)) {
			$data['branchId'] = $data['authenticateUser']->branch->branch_id;
		}
		$data['branches'] = Branch::where('status', 1)->get();
		$search = $request->all();
		try {
			if (!empty($search) && (isset($search['shipment_from']) || isset($search['shipment_type']) || isset($search['shipment_status']) || $search['from_date'] || $search['to_date'])) {
				$fromDate = Carbon::parse($request->from_date);
				$toDate = Carbon::parse($request->to_date)->addDay();

				$data['shipmentReports'] = Shipment::with('senderBranch.branchManager', 'senderBranch.branchDriver.admin', 'receiverBranch.branchManager', 'receiverBranch.branchDriver.admin', 'sender', 'receiver', 'fromCountry', 'fromState', 'fromCity', 'fromArea', 'toCountry', 'toState', 'toCity', 'toArea', 'assignToCollect', 'assignToDelivery')
					->when(isset($search['from_date']), function ($query) use ($fromDate) {
						return $query->whereDate('created_at', '>=', $fromDate);
					})
					->when(isset($search['to_date']), function ($query) use ($fromDate, $toDate) {
						return $query->whereBetween('created_at', [$fromDate, $toDate]);
					})
					->when(isset($search['branch_id']) && $search['branch_id'] != 'all', function ($query) use ($search) {
						return $query->where('sender_branch', $search['branch_id']);
					})
					->when(isset($search['shipment_from']) && $search['shipment_from'] == 'operator_country', function ($query) use ($search) {
						return $query->where('shipment_identifier', 1);
					})
					->when(isset($search['shipment_from']) && $search['shipment_from'] == 'internationally', function ($query) use ($search) {
						return $query->where('shipment_identifier', 2);
					})
					->when(isset($search['shipment_type']) && $search['shipment_type'] == 'drop_off', function ($query) use ($search) {
						$query->where('shipment_type', 'drop_off');
					})
					->when(isset($search['shipment_type']) && $search['shipment_type'] == 'pickup', function ($query) use ($search) {
						$query->where('shipment_type', 'pickup');
					})
					->when(isset($search['shipment_type']) && $search['shipment_type'] == 'condition', function ($query) use ($search) {
						$query->where('shipment_type', 'condition');
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'requested', function ($query) use ($search) {
						return $query->where('status', 0);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'canceled', function ($query) use ($search) {
						return $query->where('status', 6);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'in_queue', function ($query) use ($search) {
						return $query->where('status', 1);
					})
					->when(isset($search['shipment_status']) && ($search['shipment_status'] == 'dispatch' || $search['shipment_status'] == 'upcoming'), function ($query) use ($search) {
						return $query->where('status', 2);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'received', function ($query) use ($search) {
						return $query->where('status', 3);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'delivered', function ($query) use ($search) {
						return $query->where('status', 4);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'return_in_queue', function ($query) use ($search) {
						return $query->where('status', 8);
					})
					->when(isset($search['shipment_status']) && ($search['shipment_status'] == 'return_dispatch' || $search['shipment_status'] == 'return_upcoming'), function ($query) use ($search) {
						return $query->where('status', 9);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'return_received', function ($query) use ($search) {
						return $query->where('status', 10);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'return_delivered', function ($query) use ($search) {
						return $query->where('status', 11);
					})
					->latest()
					->get();

				return view('admin.reports.shipmentReport', $data, compact('search'));
			} else {
				$data['shipmentReports'] = null;
				return view('admin.reports.shipmentReport', $data, compact('search'));
			}
		} catch (\Exception $exception) {
			$data = ['error' => $exception->getMessage()];
			return view('admin.reports.shipmentReport', $data);
		}
	}

	public function exportShipmentReport(Request $request)
	{
		return Excel::download(new ShipmentReportExport($request), 'shipment_report.xlsx');
	}

	public function shipmentReportCount(Request $request)
	{
		$data['authenticateUser'] = Auth::guard('admin')->user();
		$data['branchId'] = null;
		if (isset($data['authenticateUser']->branch)) {
			$data['branchId'] = $data['authenticateUser']->branch->branch_id;
		}
		$data['branches'] = Branch::where('status',1)->get();
		$search = $request->all();
		try {
			if (!empty($search) && (isset($search['shipment_from']) || isset($search['shipment_type']) || isset($search['shipment_status']) || $search['from_date'] || $search['to_date'])) {
				$fromDate = Carbon::parse($request->from_date);
				$toDate = Carbon::parse($request->to_date)->addDay();

				$shipmentReports = Shipment::when(isset($search['from_date']), function ($query) use ($fromDate) {
					return $query->whereDate('created_at', '>=', $fromDate);
				})
					->when(isset($search['to_date']), function ($query) use ($fromDate, $toDate) {
						return $query->whereBetween('created_at', [$fromDate, $toDate]);
					})
					->when(isset($search['branch_id']) && $search['branch_id'] != 'all', function ($query) use ($search) {
						return $query->where('sender_branch', $search['branch_id']);
					})
					->when(isset($search['shipment_from']) && $search['shipment_from'] == 'operator_country', function ($query) use ($search) {
						return $query->where('shipment_identifier', 1);
					})
					->when(isset($search['shipment_from']) && $search['shipment_from'] == 'internationally', function ($query) use ($search) {
						return $query->where('shipment_identifier', 2);
					})
					->when(isset($search['shipment_type']) && $search['shipment_type'] == 'drop_off', function ($query) use ($search) {
						$query->where('shipment_type', 'drop_off');
					})
					->when(isset($search['shipment_type']) && $search['shipment_type'] == 'pickup', function ($query) use ($search) {
						$query->where('shipment_type', 'pickup');
					})
					->when(isset($search['shipment_type']) && $search['shipment_type'] == 'condition', function ($query) use ($search) {
						$query->where('shipment_type', 'condition');
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'requested', function ($query) use ($search) {
						return $query->where('status', 0);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'in_queue', function ($query) use ($search) {
						return $query->where('status', 1);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'dispatch', function ($query) use ($search) {
						return $query->where('status', 2);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'received', function ($query) use ($search) {
						return $query->where('status', 3);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'delivered', function ($query) use ($search) {
						return $query->where('status', 4);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'return_in_queue', function ($query) use ($search) {
						return $query->where('status', 8);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'return_dispatch', function ($query) use ($search) {
						return $query->where('status', 9);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'return_received', function ($query) use ($search) {
						return $query->where('status', 10);
					})
					->when(isset($search['shipment_status']) && $search['shipment_status'] == 'return_delivered', function ($query) use ($search) {
						return $query->where('status', 11);
					})
					->selectRaw('COUNT(*) as total_shipments')
					->selectRaw('COUNT(CASE WHEN shipments.shipment_identifier = 1 THEN shipments.id END) AS totalOperatorCountryShipments')
					->selectRaw('COUNT(CASE WHEN shipments.shipment_identifier = 2 THEN shipments.id END) AS totalInternationallyShipments')
					->selectRaw('COUNT(CASE WHEN shipments.shipment_type = "drop_off" THEN shipments.id END) AS totalDropOffShipments')
					->selectRaw('COUNT(CASE WHEN shipments.shipment_type = "pickup" THEN shipments.id END) AS totalPickupShipments')
					->selectRaw('COUNT(CASE WHEN shipments.shipment_type = "condition" THEN shipments.id END) AS totalConditionShipments')
					->selectRaw('COUNT(CASE WHEN shipments.status = 0 THEN shipments.id END) AS totalPendingShipments')
					->selectRaw('COUNT(CASE WHEN shipments.status = 1 THEN shipments.id END) AS totalInQueueShipments')
					->selectRaw('COUNT(CASE WHEN shipments.status = 2 THEN shipments.id END) AS totalDispatchShipments')
					->selectRaw('COUNT(CASE WHEN shipments.status = 3 THEN shipments.id END) AS totalDeliveryInQueueShipments')
					->selectRaw('COUNT(CASE WHEN shipments.status = 4 THEN shipments.id END) AS totalDeliveredShipments')
					->selectRaw('COUNT(CASE WHEN shipments.status = 8 THEN shipments.id END) AS totalReturnInQueueShipments')
					->selectRaw('COUNT(CASE WHEN shipments.status = 9 THEN shipments.id END) AS totalReturnInDispatchShipments')
					->selectRaw('COUNT(CASE WHEN shipments.status = 10 THEN shipments.id END) AS totalReturnDeliveryInQueueShipments')
					->selectRaw('COUNT(CASE WHEN shipments.status = 11 THEN shipments.id END) AS totalReturnInDelivered')
					->get()
					->toArray();

				$data['shipmentReportRecords'] = collect($shipmentReports)->collapse();

				return view('admin.reports.shipmentReportCount', $data, compact('search'));
			} else {
				$data['shipmentReportRecords'] = null;
				return view('admin.reports.shipmentReportCount', $data, compact('search'));
			}
		} catch (\Exception $exception) {
			$data = ['error' => $exception->getMessage()];
			return view('admin.reports.shipmentReportCount', $data);
		}

	}

	public function exportShipmentReportCount(Request $request)
	{
		return Excel::download(new ShipmentReportCountExport($request), 'shipment_report_count.xlsx');
	}

	public function shipmentTransactionReport(Request $request){
		$data['authenticateUser'] = Auth::guard('admin')->user();
		$data['branchId'] = null;
		if (isset($data['authenticateUser']->branch)) {
			$data['branchId'] = $data['authenticateUser']->branch->branch_id;
		}
		$data['branches'] = Branch::where('status',1)->get();

		$search = $request->all();

		try {
			if (!empty($search) && (isset($search['shipment_from']) || isset($search['shipment_type']) || $search['from_date'] || $search['to_date'])) {
				$fromDate = Carbon::parse($request->from_date);
				$toDate = Carbon::parse($request->to_date)->addDay();

				$shipmentTransactionReports = Transaction::when(isset($search['from_date']), function ($query) use ($fromDate) {
					return $query->whereDate('created_at', '>=', $fromDate);
				})
					->when(isset($search['to_date']), function ($query) use ($fromDate, $toDate) {
						return $query->whereBetween('created_at', [$fromDate, $toDate]);
					})
					->when(isset($search['branch_id']) && $search['branch_id'] != 'all', function ($query) use ($search) {
						return $query->where('branch_id', $search['branch_id']);
					})
					->when(isset($search['shipment_from']) && $search['shipment_from'] == 'operator_country', function ($query) use ($search) {
						return $query->where('shipment_identifier', 1);
					})
					->when(isset($search['shipment_from']) && $search['shipment_from'] == 'internationally', function ($query) use ($search) {
						return $query->where('shipment_identifier', 2);
					})
					->when(isset($search['shipment_type']) && $search['shipment_type'] == 'drop_off', function ($query) use ($search) {
						$query->where('shipment_type', 'drop_off');
					})
					->when(isset($search['shipment_type']) && $search['shipment_type'] == 'pickup', function ($query) use ($search) {
						$query->where('shipment_type', 'pickup');
					})
					->when(isset($search['shipment_type']) && $search['shipment_type'] == 'condition', function ($query) use ($search) {
						$query->where('shipment_type', 'condition');
					})
					->selectRaw('SUM(CASE WHEN shipment_id IS NOT NULL THEN amount ELSE 0 END) AS totalShipmentTransactions')
					->selectRaw('SUM(CASE WHEN shipment_identifier IS NOT NULL AND shipment_identifier = 1 THEN amount ELSE 0 END) AS totalOperatorCountryTransactions')
					->selectRaw('SUM(CASE WHEN shipment_identifier IS NOT NULL AND shipment_identifier = 2 THEN amount ELSE 0 END) AS totalInternationallyTransactions')
					->selectRaw('SUM(CASE WHEN shipment_type IS NOT NULL AND shipment_type = "drop_off" THEN amount ELSE 0 END) AS totalDropOffTransactions')
					->selectRaw('SUM(CASE WHEN shipment_type IS NOT NULL AND shipment_type = "pickup" THEN amount ELSE 0 END) AS totalPickupTransactions')
					->selectRaw('SUM(CASE WHEN shipment_type IS NOT NULL AND shipment_type = "condition" THEN amount ELSE 0 END) AS totalConditionTransactions')
					->get()
					->toArray();

				$data['shipmentTransactionReportRecords'] = collect($shipmentTransactionReports)->collapse();

				return view('admin.reports.shipmentTransactionReport', $data, compact('search'));
			} else {
				$data['shipmentTransactionReportRecords'] = null;
				return view('admin.reports.shipmentTransactionReport', $data, compact('search'));
			}
		} catch (\Exception $exception) {
			$data = ['error' => $exception->getMessage()];
			return view('admin.reports.shipmentTransactionReport', $data, compact('search'));
		}
	}

	public function exportShipmentTransactionReport(Request $request){
		return Excel::download(new ShipmentTransactionReportExport($request), 'shipment_transaction_report.xlsx');
	}

	public function shipmentList(Request $request, $status = null, $type = null)
	{
		$shipmentManagement = config('shipmentManagement');
		$types = array_keys($shipmentManagement);
		abort_if(!in_array($type, $types), 404);
		$data['title'] = $shipmentManagement[$type]['title'];
		$authenticateUser = Auth::guard('admin')->user();

		$filterData = $this->_filter($request, $status, $type, $authenticateUser);
		$data['search'] = $filterData['search'];
		$data['allShipments'] = $filterData['allShipments']
			->latest()
			->paginate(config('basic.paginate'));

		$page_title = ucwords(str_replace('_', ' ', $status));

		return view($shipmentManagement[$type]['shipment_view'], $data, compact('type', 'status', 'authenticateUser', 'page_title'));
	}

	public function _filter($request, $status, $type, $authenticateUser)
	{

		$search = $request->all();

		$shipments = Shipment::with('senderBranch.branchManager', 'senderBranch.branchDriver.admin', 'receiverBranch.branchManager', 'receiverBranch.branchDriver.admin', 'sender', 'receiver', 'fromCountry', 'fromState', 'fromCity', 'fromArea', 'toCountry', 'toState', 'toCity', 'toArea', 'assignToCollect', 'assignToDelivery')
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
			->when($type == 'operator-country' && $status == 'in_queue', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 1)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 1);
				} else {
					return $query->where('shipment_identifier', 1)->where('status', 1)->where('sender_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'operator-country' && $status == 'dispatch', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 2)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 2);
				} else {
					return $query->where('shipment_identifier', 1)->where('status', 2)->where('sender_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'operator-country' && $status == 'upcoming', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					$query->where('shipment_identifier', 1)->whereIn('status', [100, 200]); // not found upcoming shipment for driver
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 2);
				} else {
					return $query->where('shipment_identifier', 1)->where('status', 2)->where('receiver_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'operator-country' && $status == 'received', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 3)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 3);
				} else {
					return $query->where('shipment_identifier', 1)->where('status', 3)->where(function ($query) use ($authenticateUser) {
						$query->where('sender_branch', $authenticateUser->branch->branch_id)->orWhere('receiver_branch', $authenticateUser->branch->branch_id);
					});
				}
			})
			->when($type == 'operator-country' && $status == 'delivered', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 4)->where('assign_to_collect', $authenticateUser->id)->orWhere('assign_to_delivery', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 4);
				} else {
					return $query->where('shipment_identifier', 1)->where('status', 4)->where(function ($query) use ($authenticateUser) {
						$query->where('sender_branch', $authenticateUser->branch->branch_id)->orWhere('receiver_branch', $authenticateUser->branch->branch_id);
					});
				}
			})
			->when($type == 'operator-country' && $status == 'requested', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					$query->where('shipment_identifier', 1)->whereIn('status', [100, 200]); // not found requested shipment
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->whereIn('status', [0, 6]);
				} else {
					return $query->where('shipment_identifier', 1)->whereIn('status', [0, 6])->where('sender_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'operator-country' && $status == 'assign_to_collect', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 5)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 5);
				} else {
					return $query->where('shipment_identifier', 1)->where('status', 5)->where('sender_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'operator-country' && $status == 'assign_to_delivery', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 7)->where('assign_to_delivery', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 7);
				} else {
					return $query->where('shipment_identifier', 1)->where('status', 7)->where('receiver_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'operator-country' && $status == 'return_in_queue', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 8)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 8);
				} else {
					return $query->where('shipment_identifier', 1)->where('status', 8)->where('receiver_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'operator-country' && $status == 'return_in_dispatch', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 9)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 9);
				} else {
					return $query->where('shipment_identifier', 1)->where('status', 9)->where('receiver_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'operator-country' && $status == 'return_in_upcoming', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					$query->where('shipment_identifier', 1)->whereIn('status', [100, 200]); // not found upcoming shipment for driver
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 9);
				} else {
					return $query->where('shipment_identifier', 1)->where('status', 9)->where('sender_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'operator-country' && $status == 'return_in_received', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 10)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 10);
				} else {
					return $query->where('shipment_identifier', 1)->where('status', 10)->where(function ($query) use ($authenticateUser) {
						$query->where('sender_branch', $authenticateUser->branch->branch_id)->orWhere('receiver_branch', $authenticateUser->branch->branch_id);
					});
				}
			})
			->when($type == 'operator-country' && $status == 'return_in_delivered', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 11)->where('assign_to_collect', $authenticateUser->id)->orWhere('assign_to_delivery', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id)) {
					return $query->where('shipment_identifier', 1)->where('status', 11);
				} else {
					return $query->where('shipment_identifier', 1)->where('status', 11)->where(function ($query) use ($authenticateUser) {
						$query->where('sender_branch', $authenticateUser->branch->branch_id)->orWhere('receiver_branch', $authenticateUser->branch->branch_id);
					});
				}
			})
			->when($type == 'internationally' && $status == 'in_queue', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 1)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 1);
				} else {
					return $query->where('shipment_identifier', 2)->where('status', 1)->where('sender_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'internationally' && $status == 'dispatch', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 2)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 2);
				} else {
					return $query->where('shipment_identifier', 2)->where('status', 2)->where('sender_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'internationally' && $status == 'upcoming', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					$query->where('shipment_identifier', 2)->whereIn('status', [100, 200]); // not found upcoming shipment for driver
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 2);
				} else {
					return $query->where('shipment_identifier', 2)->where('status', 2)->where('receiver_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'internationally' && $status == 'received', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 3)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 3);
				} else {
					return $query->where('shipment_identifier', 2)->where('status', 3)->where(function ($query) use ($authenticateUser) {
						$query->where('sender_branch', $authenticateUser->branch->branch_id)->orWhere('receiver_branch', $authenticateUser->branch->branch_id);
					});
				}
			})
			->when($type == 'internationally' && $status == 'delivered', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 4)->where('assign_to_collect', $authenticateUser->id)->orWhere('assign_to_delivery', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 4);
				} else {
					return $query->where('shipment_identifier', 2)->where('status', 4)->where(function ($query) use ($authenticateUser) {
						$query->where('sender_branch', $authenticateUser->branch->branch_id)->orWhere('receiver_branch', $authenticateUser->branch->branch_id);
					});
				}
			})
			->when($type == 'internationally' && $status == 'requested', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					$query->where('shipment_identifier', 2)->whereIn('status', [100, 200]); // not found requested shipment
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->whereIn('status', [0, 6]);
				} else {
					return $query->where('shipment_identifier', 2)->whereIn('status', [0, 6])->where('sender_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'internationally' && $status == 'assign_to_collect', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 5)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 5);
				} else {
					return $query->where('shipment_identifier', 2)->where('status', 5)->where('sender_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'internationally' && $status == 'assign_to_delivery', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 7)->where('assign_to_delivery', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 7);
				} else {
					return $query->where('shipment_identifier', 2)->where('status', 7)->where('receiver_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'internationally' && $status == 'return_in_queue', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 8)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 8);
				} else {
					return $query->where('shipment_identifier', 2)->where('status', 8)->where('receiver_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'internationally' && $status == 'return_in_dispatch', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 9)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 9);
				} else {
					return $query->where('shipment_identifier', 2)->where('status', 9)->where('receiver_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'internationally' && $status == 'return_in_upcoming', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					$query->where('shipment_identifier', 2)->whereIn('status', [100, 200]); // not found upcoming shipment for driver
				} else if (!isset($authenticateUser->branch->branch_id, $authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 9);
				} else {
					return $query->where('shipment_identifier', 2)->where('status', 9)->where('sender_branch', $authenticateUser->branch->branch_id);
				}
			})
			->when($type == 'internationally' && $status == 'return_in_received', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 10)->where('assign_to_collect', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 10);
				} else {
					return $query->where('shipment_identifier', 2)->where('status', 10)->where(function ($query) use ($authenticateUser) {
						$query->where('sender_branch', $authenticateUser->branch->branch_id)->orWhere('receiver_branch', $authenticateUser->branch->branch_id);
					});
				}
			})
			->when($type == 'internationally' && $status == 'return_in_delivered', function ($query) use ($authenticateUser) {
				if (!isset($authenticateUser->branch->branch_id) && isset($authenticateUser->role_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 11)->where('assign_to_collect', $authenticateUser->id)->orWhere('assign_to_delivery', $authenticateUser->id);
				} else if (!isset($authenticateUser->branch->branch_id)) {
					return $query->where('shipment_identifier', 2)->where('status', 11);
				} else {
					return $query->where('shipment_identifier', 2)->where('status', 11)->where(function ($query) use ($authenticateUser) {
						$query->where('sender_branch', $authenticateUser->branch->branch_id)->orWhere('receiver_branch', $authenticateUser->branch->branch_id);
					});
				}
			});

		$data = [
			'allShipments' => $shipments,
			'search' => $search,
		];

		return $data;
	}

	public function createShipment(Request $request, $type = null)
	{
		$data['status'] = $request->input('shipment_status');
		$createShipmentType = ['operator-country', 'internationally'];
		abort_if(!in_array($type, $createShipmentType), 404);

		$data['shipmentTypeList'] = config('shipmentTypeList');

		$data['allBranches'] = Branch::where('status', 1)->get();
		$data['users'] = User::where('user_type', '!=', '0')->get();
		$data['senders'] = $data['users']->where('user_type', 1);
		$data['receivers'] = $data['users']->where('user_type', 2);
		$data['allCountries'] = Country::where('status', 1)->get();
		$data['packageList'] = Package::where('status', 1)->get();
		$data['parcelTypes'] = ParcelType::where('status', 1)->get();

		if ($type == 'operator-country') {
			$data['basicControl'] = BasicControl::with('operatorCountry')->first();
			$data['defaultShippingRateOC'] = DefaultShippingRateOperatorCountry::firstOrFail();
			return view('admin.shipments.operatorCountryShipmentCreate', $data);
		} elseif ($type == 'internationally') {
			$data['defaultShippingRateInternationally'] = DefaultShippingRateInternationally::first();
			return view('admin.shipments.internationallyShipmentCreate', $data);
		}
	}

	public function editShipment(Request $request, $id = null, $shipmentIdentifier = null)
	{
		$data['status'] = $request->input('segment');
		$data['shipment_type'] = $request->input('shipment_type');

		$data['shipmentTypeList'] = config('shipmentTypeList');
		$data['allBranches'] = Branch::where('status', 1)->get();
		$data['users'] = User::where('user_type', '!=', '0')->get();
		$data['senders'] = $data['users']->where('user_type', 1);
		$data['receivers'] = $data['users']->where('user_type', 2);
		$data['allCountries'] = Country::where('status', 1)->get();
		$data['packageList'] = Package::where('status', 1)->get();
		$data['parcelTypes'] = ParcelType::where('status', 1)->get();

		$data['singleShipment'] = Shipment::with('sender')->findOrFail($id);
		$data['shipmentAttatchments'] = ShipmentAttatchment::where('shipment_id', $id)->get()->map(function ($image) {
			$image->src = getFile($image->driver, $image->image);
			return $image;
		});

		if ($shipmentIdentifier == 1) {
			$data['basicControl'] = BasicControl::with('operatorCountry')->first();
			$data['defaultShippingRateOC'] = DefaultShippingRateOperatorCountry::first();
			return view('admin.shipments.operatorCountryShipmentEdit', $data);
		} else {
			$data['defaultShippingRateInternationally'] = DefaultShippingRateInternationally::first();
			return view('admin.shipments.internationallyShipmentEdit', $data);
		}
	}

	public function viewShipment(Request $request, $id)
	{
		$authenticateUser = Auth::guard('admin')->user();
		$data['status'] = $request->input('segment');
		$data['shipment_type'] = $request->input('shipment_type');
		$data['singleShipment'] = Shipment::with('shipmentAttachments', 'senderBranch.branchManager', 'receiverBranch', 'sender.profile', 'receiver', 'fromCountry', 'fromState', 'fromCity', 'fromArea', 'toCountry', 'toState', 'toCity', 'toArea', 'assignToCollect')->findOrFail($id);
		return view('admin.shipments.viewShipment', $data, compact('authenticateUser'));
	}

	public function shipmentInvoice(Request $request, $id)
	{
		$data['status'] = $request->input('segment');
		$data['shipment_type'] = $request->input('shipment_type');
		$data['singleShipment'] = Shipment::with('shipmentAttachments', 'senderBranch.branchManager', 'receiverBranch', 'sender.profile', 'receiver', 'fromCountry', 'fromState', 'fromCity', 'fromArea', 'toCountry', 'toState', 'toCity', 'toArea', 'assignToCollect')->findOrFail($id);

		if ($data['shipment_type'] == 'operator-country') {
			return view('admin.invoice.operatorCountry', $data);
		} else {
			return view('admin.invoice.internationally', $data);
		}
	}

	public function shipmentStore(ShipmentRequest $request, $type = null)
	{
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

			$basic = basicControl();
			$amount = $request->total_pay;
			$sender = User::findOrFail($request->sender_id);
			$date = Carbon::now();
			$msg = [
				'currency' => $basic->currency_symbol,
				'amount' => $amount,
				'shipment_id' => $shipmentId,
			];

			$action = [
				"link" => "#",
				"icon" => "fa fa-money-bill-alt text-white"
			];

			$adminAction = [
				"link" => "#",
				"icon" => "fa fa-money-bill-alt text-white"
			];

			NotifyMailService::customerSendShipmentFromBranch($shipment, $sender);

			$this->userPushNotification($sender, 'USER_NOTIFY_COURIER_SHIPMENT', $msg, $action);
			$this->userFirebasePushNotification($sender, 'USER_NOTIFY_COURIER_SHIPMENT', $msg, $action);
			$this->adminPushNotification('ADMIN_NOTIFY_COURIER_SHIPMENT', $msg, $adminAction);
			$this->adminFirebasePushNotification('ADMIN_NOTIFY_COURIER_SHIPMENT', $msg, $action);

			$this->sendMailSms($sender, $type = 'USER_MAIL_COURIER_SHIPMENT', [
				'amount' => getAmount($amount),
				'currency' => $basic->currency_symbol,
				'shipment_id' => $shipmentId,
				'date' => $date,
			]);

			return back()->with('success', 'Shipment created successfully');

		} catch (\Exception $exp) {
			DB::rollBack();
			return back()->with('error', $exp->getMessage())->withInput();
		}
	}

	public function shipmentUpdate(ShipmentRequest $request, $id)
	{
		try {
			DB::beginTransaction();
			$shipment = Shipment::findOrFail($id);
			$shipmentId = $shipment->shipment_id;
			$fillData = $request->only($shipment->getFillable());

			$fillData['receive_amount'] = $request->receive_amount != null ? $request->receive_amount : null;
			$fillData['from_city_id'] = $request->from_city_id ?? null;
			$fillData['from_area_id'] = $request->from_area_id ?? null;
			$fillData['to_city_id'] = $request->to_city_id ?? null;
			$fillData['to_area_id'] = $request->to_area_id ?? null;

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

			if ($shipment->payment_status == 2) {
				if ($request->payment_status == 1 && $request->payment_type == 'wallet') {
					$this->walletPaymentCalculation($request, $shipmentId);
				}
			}

			$shipment->fill($fillData)->save();


			$old_shipment_image = $request->old_shipment_image ?? [];
			$dbImages = ShipmentAttatchment::where('shipment_id', $id)->whereNotIn('id', $old_shipment_image)->get();
			foreach ($dbImages as $dbImage) {
				$this->fileDelete($dbImage->driver, $dbImage->image);
				$dbImage->delete();
			}

			if ($request->hasFile('shipment_image')) {
				$getShipmentAttachments = $this->storeShipmentAttatchments($request, $shipment);
				if ($getShipmentAttachments['status'] == 'error') {
					throw new \Exception($getShipmentAttachments['message']);
				}
			}

			DB::commit();

			$basic = basicControl();
			$amount = $request->total_pay;
			$sender = User::findOrFail($request->sender_id);
			$date = Carbon::now();
			$msg = [
				'currency' => $basic->currency_symbol,
				'amount' => $amount,
				'shipment_id' => $shipmentId,
			];

			$action = [
				"link" => "#",
				"icon" => "fa fa-money-bill-alt text-white"
			];

			$adminAction = [
				"link" => "#",
				"icon" => "fa fa-money-bill-alt text-white"
			];

			$this->userPushNotification($sender, 'USER_NOTIFY_UPDATE_COURIER_SHIPMENT', $msg, $action);
			$this->userFirebasePushNotification($sender, 'USER_NOTIFY_UPDATE_COURIER_SHIPMENT', $msg, $action);
			$this->adminPushNotification('ADMIN_NOTIFY_UPDATE_COURIER_SHIPMENT', $msg, $adminAction);

			$this->sendMailSms($sender, $type = 'USER_MAIL_UPDATE_COURIER_SHIPMENT', [
				'amount' => getAmount($amount),
				'currency' => $basic->currency_symbol,
				'shipment_id' => $shipmentId,
				'date' => $date,
			]);

			return back()->with('success', 'Shipment Updated successfully');

		} catch (\Exception $exp) {
			DB::rollBack();
			return back()->with('error', $exp->getMessage());
		}
	}

	public function acceptShipmentRequest($id)
	{
		try {
			DB::beginTransaction();
			$shipment = Shipment::with('sender', 'receiver', 'senderBranch.branchManager.admin')->findOrFail($id);
			$trans = strRandom();

			if ($shipment->payment_by == 1) {
				if ($shipment->payment_type == 'cash' && $shipment->payment_status == 2) {
					return back()->with('error', 'Please first complete your payment? please go to edit and select payment status paid then update this shipment.');
				} else {
					$shipment->status = 1;
					$shipment->save();
					DB::commit();
					NotifyMailService::acceptShipmentRequestNotify($shipment, $trans);
					return back()->with('success', 'Shipment request accepted successfully! This shipment is now in queue.');
				}
			} elseif ($shipment->payment_by == 2) {
				$shipment->status = 1;
				$shipment->save();
				DB::commit();
				NotifyMailService::acceptShipmentRequestNotify($shipment);
				return back()->with('success', 'Shipment request accepted successfully! This shipment is now in queue.');
			}

		} catch (\Exception $exp) {
			DB::rollBack();
			return back()->with('error', $exp->getMessage())->withInput();
		}
	}

	public function cancelShipmentRequest($id)
	{
		try {
			DB::beginTransaction();
			$basic = basicControl();
			$explodeData = explode('_', $basic->refund_time);
			$refund_time = $explodeData[0];
			$refund_time_type = strtolower($explodeData[1]);
			$func = $refund_time_type == 'minute' ? 'addMinutes' : ($refund_time_type == 'hour' ? 'addHours' : 'addDays');
			$moneyRefundTime = Carbon::now()->$func($refund_time);

			$shipment = Shipment::findOrFail($id);
			$shipment->status = 6;
			$shipment->shipment_cancel_time = Carbon::now();
			if ($shipment->payment_type == 'wallet' && $shipment->payment_status == 1) {
				$shipment->refund_time = $moneyRefundTime;
			}

			$shipment->save();
			DB::commit();
			NotifyMailService::cancelShipmentRequestNotify($shipment, $refund_time, $refund_time_type);
			return back()->with('success', 'Shipment canceled successfully!');
		} catch (\Exception $exp) {
			DB::rollBack();
			return back()->with('error', $exp->getMessage())->withInput();
		}
	}


	public function assignToCollectShipmentRequest(Request $request, $id)
	{
		$branchDriver = Admin::where('status', 1)->find($request->branch_driver_id);

		if (!$branchDriver) {
			return back()->with('error', 'Please select valid branch driver');
		}

		$shipment = Shipment::findOrFail($id);

		$shipment->assign_to_collect = $request->branch_driver_id;
		$shipment->status = 5;
		$shipment->save();

		NotifyMailService::assignToCollectPickupShipment($shipment, $branchDriver);
		return back()->with('success', 'Shipment assigned to collect successfully!');
	}

	public function assignToDeliveredShipmentRequest(Request $request, $id)
	{
		$branchDriver = Admin::where('status', 1)->find($request->branch_driver_id);
		if (!$branchDriver) {
			return back()->with('error', 'Please select valid branch driver');
		}

		$shipment = Shipment::findOrFail($id);

		$shipment->assign_to_delivery = $request->branch_driver_id;
		$shipment->status = 7;
		$shipment->save();

		NotifyMailService::assignToDeliveryPickupShipment($shipment, $branchDriver);

		return back()->with('success', 'Shipment assigned successfully for delivery!');
	}

	public function shipmentTypeList()
	{
		$data['allShipmentType'] = config('shipmentTypeList');
		return view('admin.shipmentType.index', $data);
	}

	public function shipmentTypeUpdate(Request $request, $id)
	{

		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'shipment_type' => 'required',
			'title' => 'required',
		];

		$message = [
			'shipment_type.required' => 'The shipment type field is required.',
			'title.required' => 'Title field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$filePath = base_path('config/shipmentTypeList.php');

		$shipmentTypeList = config('shipmentTypeList');

		foreach ($shipmentTypeList as & $typeList) {
			if ($typeList['id'] == $id) {
				$typeList['shipment_type'] = $request->shipment_type;
				$typeList['title'] = $request->title;
				break;
			}
		}

		$exportedArray = var_export($shipmentTypeList, true);
		$content = "<?php\n\nreturn $exportedArray;";

		file_put_contents($filePath, $content);

		// Clear cache and return response
		session()->flash('success', 'Updated Successfully');
		Artisan::call('optimize:clear');
		return back();
	}

	public function defaultRate()
	{
		$data['basicControl'] = BasicControl::with('operatorCountry')->first();
		$data['allShippingDates'] = ShippingDate::where('status', 1)->get();
		$data['allParcelTypes'] = ParcelType::where('status', 1)->get();
		$data['defaultShippingRateOperatorCountry'] = DefaultShippingRateOperatorCountry::first();

		$data['defaultShippingRateInternationally'] = DefaultShippingRateInternationally::first();
		return view('admin.shippingRate.defaultRate', $data);
	}

	public function defaultShippingRateOperatorCountryUpdate(Request $request, $id)
	{

		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'operator_country_id' => ['required', 'exists:countries,id'],
			'shipping_date_id' => ['required', 'exists:shipping_dates,id'],
			'pickup_cost' => ['numeric', 'min:0'],
			'supply_cost' => ['numeric', 'min:0'],
			'shipping_cost' => ['numeric', 'min:0'],
			'return_shipment_cost' => ['numeric', 'min:0'],
			'default_tax' => ['numeric', 'min:0'],
			'default_insurance' => ['numeric', 'min:0'],
		];

		$message = [
			'operator_country_id.required' => 'Please select a operator country',
			'shipping_date_id.required' => 'Please select shipping date',
			'parcel_type_id.required' => 'Please select a parcel type',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$defaultShippingRateOperatorCountry = DefaultShippingRateOperatorCountry::findOrFail($id);

		$defaultShippingRateOperatorCountry->country_id = $request->operator_country_id;
		$defaultShippingRateOperatorCountry->shipping_date_id = $request->shipping_date_id;
		$defaultShippingRateOperatorCountry->pickup_cost = $request->pickup_cost;
		$defaultShippingRateOperatorCountry->supply_cost = $request->supply_cost;
		$defaultShippingRateOperatorCountry->shipping_cost = $request->shipping_cost;
		$defaultShippingRateOperatorCountry->return_shipment_cost = $request->return_shipment_cost;
		$defaultShippingRateOperatorCountry->default_tax = $request->default_tax;
		$defaultShippingRateOperatorCountry->default_insurance = $request->default_insurance;

		$defaultShippingRateOperatorCountry->save();

		return back()->with('success', 'Default rate update successfully');
	}

	public function defaultShippingRateInternationallyUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'shipping_date_id' => ['required', 'exists:shipping_dates,id'],
			'pickup_cost' => ['numeric', 'min:0'],
			'supply_cost' => ['numeric', 'min:0'],
			'shipping_cost' => ['numeric', 'min:0'],
			'return_shipment_cost' => ['numeric', 'min:0'],
			'default_tax' => ['numeric', 'min:0'],
			'default_insurance' => ['numeric', 'min:0'],
		];

		$message = [
			'shipping_date_id.required' => 'Please select a Shipping date',
			'parcel_type_id.required' => 'Please select a parcel type',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$defaultShippingRateInternationally = DefaultShippingRateInternationally::findOrFail($id);

		$defaultShippingRateInternationally->shipping_date_id = $request->shipping_date_id;
		$defaultShippingRateInternationally->pickup_cost = $request->pickup_cost;
		$defaultShippingRateInternationally->supply_cost = $request->supply_cost;
		$defaultShippingRateInternationally->shipping_cost = $request->shipping_cost;
		$defaultShippingRateInternationally->return_shipment_cost = $request->return_shipment_cost;
		$defaultShippingRateInternationally->default_tax = $request->default_tax;
		$defaultShippingRateInternationally->default_insurance = $request->default_insurance;

		$defaultShippingRateInternationally->save();
		Session::flash('active-tab', 'tab2');
		return back()->with('success', 'Default rate internationally update successfully');
	}

	public function operatorCountryRate(Request $request, $type = null)
	{
		$operatorCountryShippingRateManagement = config('operatorCountryShippingRateManagement');
		$types = array_keys($operatorCountryShippingRateManagement);
		abort_if(!in_array($type, $types), 404);
		$data['title'] = $operatorCountryShippingRateManagement[$type]['title'];

		$data['shippingRateList'] = ShippingRateOperatorCountry::with('fromState', 'toState', 'parcelType')
			->when($type == 'state', function ($query) {
				$query->whereNull(['from_city_id', 'from_area_id']);
			})
			->when($type == 'city', function ($query) {
				$query->whereNotNull('from_city_id')->whereNull('from_area_id');
			})
			->when($type == 'area', function ($query) {
				$query->whereNotNull(['from_area_id']);
			})
			->groupBy('parcel_type_id')
			->paginate(config('basic.paginate'));
		return view($operatorCountryShippingRateManagement[$type]['shipping_rate_view'], $data);
	}

	public function operatorCountryShowRate(Request $request, $type = null, $id = null)
	{
		$search = $request->all();
		$operatorCountryShowShippingRateManagement = config('operatorCountryShowShippingRateManagement');
		$types = array_keys($operatorCountryShowShippingRateManagement);
		abort_if(!in_array($type, $types), 404);
		$data['title'] = $operatorCountryShowShippingRateManagement[$type]['title'];

		$data['allParcelTypes'] = ParcelType::where('status', 1)->get();

		$data['showShippingRateList'] = ShippingRateOperatorCountry::with('fromState', 'toState', 'fromCity', 'toCity', 'fromArea', 'toArea', 'parcelType')
			->when(isset($search['from_state']), function ($query) use ($search) {
				$query->whereHas('fromState', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['from_state']}[[:>:]]'");
				});
			})
			->when(isset($search['to_state']), function ($query) use ($search) {
				$query->whereHas('toState', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['to_state']}[[:>:]]'");
				});
			})
			->when(isset($search['from_city']), function ($query) use ($search) {
				$query->whereHas('fromCity', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['from_city']}[[:>:]]'");
				});
			})
			->when(isset($search['to_city']), function ($query) use ($search) {
				$query->whereHas('toCity', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['to_city']}[[:>:]]'");
				});
			})
			->when(isset($search['from_area']), function ($query) use ($search) {
				$query->whereHas('fromArea', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['from_area']}[[:>:]]'");
				});
			})
			->when(isset($search['to_area']), function ($query) use ($search) {
				$query->whereHas('toArea', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['to_area']}[[:>:]]'");
				});
			})
			->when($type == 'state-list', function ($query) {
				$query->whereNull(['from_city_id', 'from_area_id']);
			})
			->when($type == 'city-list', function ($query) {
				$query->whereNotNull('from_city_id')->whereNull('from_area_id');
			})
			->when($type == 'area-list', function ($query) {
				$query->whereNotNull('from_area_id');
			})
			->where('parcel_type_id', $id)
			->paginate(config('basic.paginate'));

		return view($operatorCountryShowShippingRateManagement[$type]['show_shipping_rate_view'], $data);
	}


	public function stateRateUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'from_state_id' => ['required', 'exists:states,id'],
			'to_state_id' => ['required', 'exists:states,id'],
			'parcel_type_id' => ['required', 'exists:parcel_types,id'],
			'shipping_cost' => ['nullable', 'numeric', 'min:0'],
			'return_shipment_cost' => ['nullable', 'numeric', 'min:0'],
			'tax' => ['nullable', 'numeric', 'min:0'],
			'insurance' => ['nullable', 'numeric', 'min:0'],
			'cash_on_delivery_cost' => ['nullable', 'numeric', 'min:0'],
		];

		$message = [
			'from_state_id.required' => 'Please select from state',
			'to_state_id.required' => 'Please select to state',
			'parcel_type_id.required' => 'Please select parcel type',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$operatorCountry = ShippingRateOperatorCountry::findOrFail($id);

		$operatorCountry->from_state_id = $request->from_state_id;
		$operatorCountry->to_state_id = $request->to_state_id;
		$operatorCountry->parcel_type_id = $request->parcel_type_id;
		$operatorCountry->shipping_cost = $request->shipping_cost == null ? 0 : $request->shipping_cost;
		$operatorCountry->return_shipment_cost = $request->return_shipment_cost == null ? 0 : $request->return_shipment_cost;
		$operatorCountry->tax = $request->tax == null ? 0 : $request->tax;
		$operatorCountry->insurance = $request->insurance == null ? 0 : $request->insurance;
		$operatorCountry->cash_on_delivery_cost = $request->cash_on_delivery_cost == null ? 0 : $request->cash_on_delivery_cost;

		$operatorCountry->save();

		return back()->with('success', 'Shipping rate Update successfully');
	}

	public function cityRateUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'from_state_id' => ['required', 'exists:states,id'],
			'to_state_id' => ['required', 'exists:states,id'],
			'from_city_id' => ['required', 'exists:cities,id'],
			'to_city_id' => ['required', 'exists:cities,id'],
			'parcel_type_id' => ['required', 'exists:parcel_types,id'],
			'shipping_cost' => ['nullable', 'numeric', 'min:0'],
			'return_shipment_cost' => ['nullable', 'numeric', 'min:0'],
			'tax' => ['nullable', 'numeric', 'min:0'],
			'insurance' => ['nullable', 'numeric', 'min:0'],
			'cash_on_delivery_cost' => ['nullable', 'numeric', 'min:0'],
		];

		$message = [
			'from_state_id.required' => 'Please select from state',
			'to_state_id.required' => 'Please select to state',
			'from_city_id.required' => 'Please select from city',
			'to_city_id.required' => 'Please select to city',
			'parcel_type_id.required' => 'Please select parcel type',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$operatorCountry = ShippingRateOperatorCountry::findOrFail($id);

		$operatorCountry->from_state_id = $request->from_state_id;
		$operatorCountry->to_state_id = $request->to_state_id;
		$operatorCountry->from_city_id = $request->from_city_id;
		$operatorCountry->to_city_id = $request->to_city_id;
		$operatorCountry->parcel_type_id = $request->parcel_type_id;
		$operatorCountry->shipping_cost = $request->shipping_cost == null ? 0 : $request->shipping_cost;
		$operatorCountry->return_shipment_cost = $request->return_shipment_cost == null ? 0 : $request->return_shipment_cost;
		$operatorCountry->tax = $request->tax == null ? 0 : $request->tax;
		$operatorCountry->insurance = $request->insurance == null ? 0 : $request->insurance;
		$operatorCountry->cash_on_delivery_cost = $request->cash_on_delivery_cost == null ? 0 : $request->cash_on_delivery_cost;

		$operatorCountry->save();

		return back()->with('success', 'Shipping rate Update successfully');
	}

	public function areaRateUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'from_state_id' => ['required', 'exists:states,id'],
			'to_state_id' => ['required', 'exists:states,id'],
			'from_city_id' => ['required', 'exists:cities,id'],
			'to_city_id' => ['required', 'exists:cities,id'],
			'from_area_id' => ['required', 'exists:areas,id'],
			'to_area_id' => ['required', 'exists:areas,id'],
			'parcel_type_id' => ['required', 'exists:parcel_types,id'],
			'shipping_cost' => ['nullable', 'numeric', 'min:0'],
			'return_shipment_cost' => ['nullable', 'numeric', 'min:0'],
			'tax' => ['nullable', 'numeric', 'min:0'],
			'insurance' => ['nullable', 'numeric', 'min:0'],
			'cash_on_delivery_cost' => ['nullable', 'numeric', 'min:0'],
		];

		$message = [
			'from_state_id.required' => 'Please select from state',
			'to_state_id.required' => 'Please select to state',
			'from_city_id.required' => 'Please select from city',
			'to_city_id.required' => 'Please select to city',
			'from_area_id.required' => 'Please select from area',
			'to_area_id.required' => 'Please select to area',
			'parcel_type_id.required' => 'Please select parcel type',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$operatorCountry = ShippingRateOperatorCountry::findOrFail($id);

		$operatorCountry->from_state_id = $request->from_state_id;
		$operatorCountry->to_state_id = $request->to_state_id;
		$operatorCountry->from_city_id = $request->from_city_id;
		$operatorCountry->to_city_id = $request->to_city_id;
		$operatorCountry->from_area_id = $request->from_area_id;
		$operatorCountry->to_area_id = $request->to_area_id;
		$operatorCountry->parcel_type_id = $request->parcel_type_id;
		$operatorCountry->shipping_cost = $request->shipping_cost == null ? 0 : $request->shipping_cost;
		$operatorCountry->return_shipment_cost = $request->return_shipment_cost == null ? 0 : $request->return_shipment_cost;
		$operatorCountry->tax = $request->tax == null ? 0 : $request->tax;
		$operatorCountry->insurance = $request->insurance == null ? 0 : $request->insurance;
		$operatorCountry->cash_on_delivery_cost = $request->cash_on_delivery_cost == null ? 0 : $request->cash_on_delivery_cost;

		$operatorCountry->save();

		return back()->with('success', 'Shipping rate Update successfully');
	}


	public function createShippingRateOperatorCountry()
	{
		$data['allCountries'] = Country::where('status', 1)->get();
		$data['basicControl'] = BasicControl::with('operatorCountry')->first();
		$data['allParcelTypes'] = ParcelType::where('status', 1)->get();
		return view('admin.shippingRate.operatorCountry.create', $data);
	}

	public function shippingRateOperatorCountryStore(Request $request, $type = null)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'operator_country_id' => ['required', 'exists:countries,id'],
			'from_state_id' => ['required', 'exists:states,id'],
			'to_state_id' => ['required', 'exists:states,id'],
			'parcel_type_id' => ['required', 'exists:parcel_types,id'],
			'shipping_cost' => ['nullable', 'numeric', 'min:0'],
			'return_shipment_cost' => ['nullable', 'numeric', 'min:0'],
			'tax' => ['nullable', 'numeric', 'min:0'],
			'insurance' => ['nullable', 'numeric', 'min:0'],
			'cash_on_delivery_cost' => ['nullable', 'numeric', 'min:0'],
		];

		$message = [
			'operator_country_id.required' => 'Select Operator Country',
			'from_state_id.required' => 'Please select from state',
			'to_state_id.required' => 'Please select to state',
			'parcel_type_id.required' => 'Please select parcel type',
		];

		if ($type == 'city-wise') {
			$rules['from_city_id'] = ['required', 'exists:cities,id'];
			$rules['to_city_id'] = ['required', 'exists:cities,id'];
			$message['from_city_id.required'] = 'please select from city';
			$message['to_city_id.required'] = 'please select to city';
		} elseif ($type == 'area-wise') {
			$rules['from_area_id'] = ['required', 'exists:areas,id'];
			$rules['to_area_id'] = ['required', 'exists:areas,id'];
			$message['from_area_id.required'] = 'please select from area';
			$message['to_area_id.required'] = 'please select to area';
		}

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$operatorCountry = new ShippingRateOperatorCountry();

		$operatorCountry->country_id = $request->operator_country_id;
		$operatorCountry->from_state_id = $request->from_state_id;
		$operatorCountry->to_state_id = $request->to_state_id;
		$operatorCountry->parcel_type_id = $request->parcel_type_id;
		$operatorCountry->shipping_cost = $request->shipping_cost == null ? 0 : $request->shipping_cost;
		$operatorCountry->return_shipment_cost = $request->return_shipment_cost == null ? 0 : $request->return_shipment_cost;
		$operatorCountry->tax = $request->tax == null ? 0 : $request->tax;
		$operatorCountry->insurance = $request->insurance == null ? 0 : $request->insurance;
		$operatorCountry->cash_on_delivery_cost = $request->cash_on_delivery_cost == null ? 0 : $request->cash_on_delivery_cost;

		if ($type == 'city-wise') {
			$operatorCountry->from_city_id = $request->from_city_id;
			$operatorCountry->to_city_id = $request->to_city_id;
		} elseif ($type == 'area-wise') {
			$operatorCountry->from_city_id = $request->from_city_id;
			$operatorCountry->to_city_id = $request->to_city_id;
			$operatorCountry->from_area_id = $request->from_area_id;
			$operatorCountry->to_area_id = $request->to_area_id;
		}

		$operatorCountry->save();

		return back()->with('success', 'Shipping rate created successfully');
	}

	public function deleteStateRate($id)
	{
		ShippingRateOperatorCountry::findOrFail($id)->delete();
		return back()->with('success', 'Shipping rate deleted successfully!');
	}

	public function deleteCityRate($id)
	{
		ShippingRateOperatorCountry::findOrFail($id)->delete();
		return back()->with('success', 'Shipping rate deleted successfully!');
	}

	public function deleteAreaRate($id)
	{
		ShippingRateOperatorCountry::findOrFail($id)->delete();
		return back()->with('success', 'Shipping rate deleted successfully!');
	}


	public function internationallyRate(Request $request, $type = null)
	{
		$internationallyShippingRateManagement = config('internationallyShippingRateManagement');
		$types = array_keys($internationallyShippingRateManagement);
		abort_if(!in_array($type, $types), 404);
		$data['title'] = $internationallyShippingRateManagement[$type]['title'];

		$data['shippingRateList'] = ShippingRateInternationally::with('fromCountry', 'toCountry', 'parcelType')
			->when($type == 'country', function ($query) {
				$query->whereNull(['from_state_id', 'from_city_id']);
			})
			->when($type == 'state', function ($query) {
				$query->whereNotNull('from_state_id')->whereNull('from_city_id');
			})
			->when($type == 'city', function ($query) {
				$query->whereNotNull(['from_city_id']);
			})
			->groupBy('parcel_type_id')
			->paginate(config('basic.paginate'));
		return view($internationallyShippingRateManagement[$type]['shipping_rate_view'], $data);
	}


	public function createShippingRateInternationally()
	{
		$data['allCountries'] = Country::where('status', 1)->get();
		$data['allParcelTypes'] = ParcelType::where('status', 1)->get();
		return view('admin.shippingRate.internationally.create', $data);
	}

	public function shippingRateInternationallyStore(Request $request, $type = null)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'from_country_id' => ['required', 'exists:countries,id'],
			'to_country_id' => ['required', 'exists:countries,id'],
			'parcel_type_id' => ['required', 'exists:parcel_types,id'],
			'shipping_cost' => ['nullable', 'numeric', 'min:0'],
			'return_shipment_cost' => ['nullable', 'numeric', 'min:0'],
			'tax' => ['nullable', 'numeric', 'min:0'],
			'insurance' => ['nullable', 'numeric', 'min:0'],
		];

		$message = [
			'from_country_id.required' => 'Please select from country',
			'to_country_id.required' => 'Please select to country',
			'parcel_type_id.required' => 'Please select parcel type',
		];

		if ($type == 'state-wise') {
			$rules['from_state_id'] = ['required', 'exists:states,id'];
			$rules['to_state_id'] = ['required', 'exists:states,id'];
			$message['from_state_id.required'] = 'please select from state';
			$message['to_state_id.required'] = 'please select to state';
		} elseif ($type == 'city-wise') {
			$rules['from_city_id'] = ['required', 'exists:cities,id'];
			$rules['to_city_id'] = ['required', 'exists:cities,id'];
			$message['from_city_id.required'] = 'please select from city';
			$message['to_city_id.required'] = 'please select to city';
		}

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$internationallyRate = new ShippingRateInternationally();

		$internationallyRate->from_country_id = $request->from_country_id;
		$internationallyRate->to_country_id = $request->to_country_id;
		$internationallyRate->parcel_type_id = $request->parcel_type_id;
		$internationallyRate->shipping_cost = $request->shipping_cost == null ? 0 : $request->shipping_cost;
		$internationallyRate->return_shipment_cost = $request->return_shipment_cost == null ? 0 : $request->return_shipment_cost;
		$internationallyRate->tax = $request->tax == null ? 0 : $request->tax;
		$internationallyRate->insurance = $request->insurance == null ? 0 : $request->insurance;

		if ($type == 'state-wise') {
			$internationallyRate->from_state_id = $request->from_state_id;
			$internationallyRate->to_state_id = $request->to_state_id;
		} elseif ($type == 'city-wise') {
			$internationallyRate->from_state_id = $request->from_state_id;
			$internationallyRate->to_state_id = $request->to_state_id;
			$internationallyRate->from_city_id = $request->from_city_id;
			$internationallyRate->to_city_id = $request->to_city_id;
		}

		$internationallyRate->save();

		return back()->with('success', 'Shipping rate added successfully');
	}

	public function internationallyShowRate(Request $request, $type = null, $id = null)
	{
		$search = $request->all();
		$internationallyShowShippingRateManagement = config('internationallyShowShippingRateManagement');
		$types = array_keys($internationallyShowShippingRateManagement);
		abort_if(!in_array($type, $types), 404);
		$data['title'] = $internationallyShowShippingRateManagement[$type]['title'];

		$data['allCountries'] = Country::where('status', 1)->get();
		$data['allParcelTypes'] = ParcelType::where('status', 1)->get();

		$data['showShippingRateList'] = ShippingRateInternationally::with('fromCountry', 'toCountry', 'fromState', 'toState', 'fromCity', 'toCity', 'parcelType')
			->when(isset($search['from_country']), function ($query) use ($search) {
				$query->whereHas('fromCountry', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['from_country']}[[:>:]]'");
				});
			})
			->when(isset($search['to_country']), function ($query) use ($search) {
				$query->whereHas('toCountry', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['to_country']}[[:>:]]'");
				});
			})
			->when(isset($search['from_state']), function ($query) use ($search) {
				$query->whereHas('fromState', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['from_state']}[[:>:]]'");
				});
			})
			->when(isset($search['to_state']), function ($query) use ($search) {
				$query->whereHas('toState', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['to_state']}[[:>:]]'");
				});
			})
			->when(isset($search['from_city']), function ($query) use ($search) {
				$query->whereHas('fromCity', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['from_city']}[[:>:]]'");
				});
			})
			->when(isset($search['to_city']), function ($query) use ($search) {
				$query->whereHas('toCity', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['to_city']}[[:>:]]'");
				});
			})
			->when($type == 'country-list', function ($query) {
				$query->whereNull(['from_state_id', 'from_city_id']);
			})
			->when($type == 'state-list', function ($query) {
				$query->whereNotNull('from_state_id')->whereNull('from_city_id');
			})
			->when($type == 'city-list', function ($query) {
				$query->whereNotNull('from_city_id');
			})
			->where('parcel_type_id', $id)
			->paginate(config('basic.paginate'));

		return view($internationallyShowShippingRateManagement[$type]['show_shipping_rate_view'], $data);
	}


	public function countryRateUpdateInternationally(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'from_country_id' => ['required', 'exists:countries,id'],
			'to_country_id' => ['required', 'exists:countries,id'],
			'parcel_type_id' => ['required', 'exists:parcel_types,id'],
			'shipping_cost' => ['nullable', 'numeric', 'min:0'],
			'return_shipment_cost' => ['nullable', 'numeric', 'min:0'],
			'tax' => ['nullable', 'numeric', 'min:0'],
			'insurance' => ['nullable', 'numeric', 'min:0'],
		];

		$message = [
			'from_country_id.required' => 'Please select from country',
			'to_country_id.required' => 'Please select to country',
			'parcel_type_id.required' => 'Please select parcel type',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$internationallyRate = ShippingRateInternationally::findOrFail($id);

		$internationallyRate->from_country_id = $request->from_country_id;
		$internationallyRate->to_country_id = $request->to_country_id;
		$internationallyRate->parcel_type_id = $request->parcel_type_id;
		$internationallyRate->shipping_cost = $request->shipping_cost == null ? 0 : $request->shipping_cost;
		$internationallyRate->return_shipment_cost = $request->return_shipment_cost == null ? 0 : $request->return_shipment_cost;
		$internationallyRate->tax = $request->tax == null ? 0 : $request->tax;
		$internationallyRate->insurance = $request->insurance == null ? 0 : $request->insurance;

		$internationallyRate->save();

		return back()->with('success', 'Shipping rate Update successfully');
	}

	public function stateRateUpdateInternationally(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));


		$rules = [
			'from_country_id' => ['required', 'exists:countries,id'],
			'to_country_id' => ['required', 'exists:countries,id'],
			'from_state_id' => ['required', 'exists:states,id'],
			'to_state_id' => ['required', 'exists:states,id'],
			'parcel_type_id' => ['required', 'exists:parcel_types,id'],
			'shipping_cost' => ['nullable', 'numeric', 'min:0'],
			'return_shipment_cost' => ['nullable', 'numeric', 'min:0'],
			'tax' => ['nullable', 'numeric', 'min:0'],
			'insurance' => ['nullable', 'numeric', 'min:0'],
		];

		$message = [
			'from_country_id.required' => 'Please select from country',
			'to_country_id.required' => 'Please select to country',
			'from_state_id.required' => 'Please select from state',
			'to_state_id.required' => 'Please select to state',
			'parcel_type_id.required' => 'Please select parcel type',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$internationallyRate = ShippingRateInternationally::findOrFail($id);

		$internationallyRate->from_country_id = $request->from_country_id;
		$internationallyRate->to_country_id = $request->to_country_id;
		$internationallyRate->from_state_id = $request->from_state_id;
		$internationallyRate->to_state_id = $request->to_state_id;
		$internationallyRate->parcel_type_id = $request->parcel_type_id;
		$internationallyRate->shipping_cost = $request->shipping_cost == null ? 0 : $request->shipping_cost;
		$internationallyRate->return_shipment_cost = $request->return_shipment_cost == null ? 0 : $request->return_shipment_cost;
		$internationallyRate->tax = $request->tax == null ? 0 : $request->tax;
		$internationallyRate->insurance = $request->insurance == null ? 0 : $request->insurance;

		$internationallyRate->save();

		return back()->with('success', 'Shipping rate Update successfully');
	}


	public function cityRateUpdateInternationally(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));


		$rules = [
			'from_country_id' => ['required', 'exists:countries,id'],
			'to_country_id' => ['required', 'exists:countries,id'],
			'from_state_id' => ['required', 'exists:states,id'],
			'to_state_id' => ['required', 'exists:states,id'],
			'from_city_id' => ['required', 'exists:cities,id'],
			'to_city_id' => ['required', 'exists:cities,id'],
			'parcel_type_id' => ['required', 'exists:parcel_types,id'],
			'shipping_cost' => ['nullable', 'numeric', 'min:0'],
			'return_shipment_cost' => ['nullable', 'numeric', 'min:0'],
			'tax' => ['nullable', 'numeric', 'min:0'],
			'insurance' => ['nullable', 'numeric', 'min:0'],
		];

		$message = [
			'from_country_id.required' => 'Please select from country',
			'to_country_id.required' => 'Please select to country',
			'from_state_id.required' => 'Please select from state',
			'to_state_id.required' => 'Please select to state',
			'from_city_id.required' => 'Please select from city',
			'to_city_id.required' => 'Please select to city',
			'parcel_type_id.required' => 'Please select parcel type',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		$internationallyRate = ShippingRateInternationally::findOrFail($id);

		$internationallyRate->from_country_id = $request->from_country_id;
		$internationallyRate->to_country_id = $request->to_country_id;
		$internationallyRate->from_state_id = $request->from_state_id;
		$internationallyRate->to_state_id = $request->to_state_id;
		$internationallyRate->from_city_id = $request->from_city_id;
		$internationallyRate->to_city_id = $request->to_city_id;
		$internationallyRate->parcel_type_id = $request->parcel_type_id;
		$internationallyRate->shipping_cost = $request->shipping_cost == null ? 0 : $request->shipping_cost;
		$internationallyRate->return_shipment_cost = $request->return_shipment_cost == null ? 0 : $request->return_shipment_cost;
		$internationallyRate->tax = $request->tax == null ? 0 : $request->tax;
		$internationallyRate->insurance = $request->insurance == null ? 0 : $request->insurance;

		$internationallyRate->save();

		return back()->with('success', 'Shipping rate Update successfully');
	}

	public function deleteICountryRate($id)
	{
		ShippingRateInternationally::findOrFail($id)->delete();
		return back()->with('success', 'Shipping rate deleted successfully!');
	}

	public function deleteIStateRate($id)
	{
		ShippingRateInternationally::findOrFail($id)->delete();
		return back()->with('success', 'Shipping rate deleted successfully!');
	}

	public function deleteICityRate($id)
	{
		ShippingRateInternationally::findOrFail($id)->delete();
		return back()->with('success', 'Shipping rate deleted successfully!');
	}


	public function shippingDateStore(Request $request)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'shipping_days' => ['required', 'numeric', 'min:0'],
		];

		$message = [
			'shipping_days.required' => 'Shipping date is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->shipping_days == null) {
			return back()->with('error', 'Shipping date is required');
		}

		$shippingDate = new ShippingDate();
		$shippingDate->shipping_days = $request->shipping_days;
		$shippingDate->status = $request->status;
		$shippingDate->save();

		return back()->with('success', 'Shipping Date Created Successfully!');
	}


	public function OCGetSelectedLocationShipRate(Request $request)
	{
		$parcelTypeId = $request->parcelTypeId;

		if ($request->fromCountryId == null && $request->toCountryId == null) {
			$operatorCountryId = BasicControl::first('operator_country');
			if ($request->fromStateId != null && $request->toStateId != null && $request->fromCityId == null && $request->fromAreaId == null) {
				$shippingRate = ShippingRateOperatorCountry::where('country_id', $operatorCountryId->operator_country)
					->when($parcelTypeId != '', function ($query) use ($parcelTypeId) {
						$query->where('parcel_type_id', $parcelTypeId);
					})
					->where([
						['from_state_id', '=', $request->fromStateId],
						['to_state_id', '=', $request->toStateId]
					])
					->whereNull(['from_city_id', 'to_city_id'])
					->first();
				return response($shippingRate);
			} elseif ($request->fromStateId != null && $request->toStateId != null && $request->fromCityId != null && $request->toCityId != null && $request->fromAreaId == null && $request->toAreaId == null) {
				$shippingRate = ShippingRateOperatorCountry::where('country_id', $operatorCountryId->operator_country)
					->when($parcelTypeId != '', function ($query) use ($parcelTypeId) {
						$query->where('parcel_type_id', $parcelTypeId);
					})
					->where([
						['from_state_id', '=', $request->fromStateId],
						['to_state_id', '=', $request->toStateId],
						['from_city_id', '=', $request->fromCityId],
						['to_city_id', '=', $request->toCityId]
					])
					->whereNull(['from_area_id', 'to_area_id'])
					->first();
				return response($shippingRate);
			} elseif ($request->fromStateId != null && $request->toStateId != null && $request->fromCityId != null && $request->toCityId != null && $request->fromAreaId != null && $request->toAreaId != null) {
				$shippingRate = ShippingRateOperatorCountry::where('country_id', $operatorCountryId->operator_country)
					->when($parcelTypeId != '', function ($query) use ($parcelTypeId) {
						$query->where('parcel_type_id', $parcelTypeId);
					})
					->where([
						['from_state_id', '=', $request->fromStateId],
						['to_state_id', '=', $request->toStateId],
						['from_city_id', '=', $request->fromCityId],
						['to_city_id', '=', $request->toCityId],
						['from_area_id', '=', $request->fromAreaId],
						['to_area_id', '=', $request->toAreaId]
					])
					->first();
				return response($shippingRate);
			}
		} elseif ($request->fromCountryId != null && $request->toCountryId != null) {
			if ($request->fromCountryId != null && $request->toCountryId != null && $request->fromStateId == null && $request->toStateId == null) {
				$shippingRate = ShippingRateInternationally::where([
					['from_country_id', '=', $request->fromCountryId],
					['to_country_id', '=', $request->toCountryId]
				])
					->whereNull(['from_state_id', 'to_state_id'])
					->where('parcel_type_id', $parcelTypeId)
					->first();
				return response($shippingRate);
			} elseif ($request->fromCountryId != null && $request->toCountryId != null && $request->fromStateId != null && $request->toStateId != null && $request->fromCityId == null && $request->toCityId == null) {
				$shippingRate = ShippingRateInternationally::where([
					['from_country_id', '=', $request->fromCountryId],
					['to_country_id', '=', $request->toCountryId],
					['from_state_id', '=', $request->fromStateId],
					['to_state_id', '=', $request->toStateId],
				])
					->whereNull(['from_city_id', 'to_city_id'])
					->where('parcel_type_id', $parcelTypeId)
					->first();
				return response($shippingRate);
			} elseif ($request->fromCountryId != null && $request->toCountryId != null && $request->fromStateId != null && $request->toStateId != null && $request->fromCityId != null && $request->toCityId != null) {
				$shippingRate = ShippingRateInternationally::where([
					['from_country_id', '=', $request->fromCountryId],
					['to_country_id', '=', $request->toCountryId],
					['from_state_id', '=', $request->fromStateId],
					['to_state_id', '=', $request->toStateId],
					['from_city_id', '=', $request->fromCityId],
					['to_city_id', '=', $request->toCityId],
				])
					->where('parcel_type_id', $parcelTypeId)
					->first();
				return response($shippingRate);
			}
		}
	}

	public function deleteShipment($id)
	{
		$authenticateAdmin = Auth::guard('admin')->user();
		$shipment = Shipment::withTrashed()->findOrFail($id);
		$deletedByArray = $shipment->deleted_by;
		$deletedByArray[] = $authenticateAdmin->id;
		$shipment->deleted_by = $deletedByArray;
		$shipment->save();
		$shipment->delete();
		return back()->with('success', 'Shipment deleted successfully!');
	}

	public function restoreShipment($id)
	{
		Shipment::onlyTrashed()->findOrFail($id)->restore();
		return back()->with('success', 'Shipment restore successfully!');
	}

	public function forceDeleteShipment($id)
	{
		Shipment::onlyTrashed()->findOrFail($id)->forceDelete();
		return back()->with('success', 'shipment is permanent deleted!');
	}

	public function updateShipmentStatus($id, $type = null)
	{
		try {
			DB::beginTransaction();
			$shipment = Shipment::with('senderBranch.branchManager', 'receiverBranch', 'sender', 'receiver')->findOrFail($id);
			$trans = strRandom();
			$time = Carbon::now();
			if ($type == 'dispatch') {
				if ($shipment->payment_by == 1) {
					if ($shipment->payment_type == 'cash' && $shipment->payment_status == 2) {
						return back()->with('error', 'Please first complete your payment? go to edit and select payment status paid then update this shipment');
					} elseif (($shipment->payment_type == 'wallet' || $shipment->payment_type == 'cash') && $shipment->payment_status == 1) {

						$this->shipmentStatusUpdate($shipment, 2, 'dispatch', $time);
						DB::commit();
						$transaction = new Transaction();
						$remarks = "The sender completes the payment for the dispatched shipment";
						TransactionService::shipmentTransaction($shipment, $transaction, $trans, $shipment->sender_branch, optional($shipment->sender)->id, $remarks);
						NotifyMailService::dispatchShipmentRequest($shipment);
						return back()->with('success', 'Shipment Dispatched Successfully!');
					}
				} elseif ($shipment->payment_by == 2) {
					if ($shipment->payment_type == 'cash' && $shipment->payment_status == 2) {
						$this->shipmentStatusUpdate($shipment, 2, 'dispatch', $time);
						DB::commit();
						NotifyMailService::dispatchShipmentRequest($shipment);
						return back()->with('success', 'Shipment Dispatched Successfully!');
					} elseif (($shipment->payment_type == 'wallet' || $shipment->payment_type == 'cash') && $shipment->payment_status == 1) {
						$this->shipmentStatusUpdate($shipment, 2, 'dispatch', $time);
						DB::commit();
						$transaction = new Transaction();
						$remarks = "The receiver completes the payment for the dispatched shipment";
						TransactionService::shipmentTransaction($shipment, $transaction, $trans, $shipment->sender_branch, optional($shipment->receiver)->id, $remarks);
						NotifyMailService::dispatchShipmentRequest($shipment);
						return back()->with('success', 'Shipment Dispatched Successfully!');
					}
				}
			} elseif ($type == 'received') {
				$this->shipmentStatusUpdate($shipment, 3, 'received', $time);
				DB::commit();
				NotifyMailService::receiveShipmentRequest($shipment);
				return back()->with('success', 'Shipment Received Successfully!');
			} elseif ($type == 'delivered') {
				if ($shipment->payment_by == 2) {
					if ($shipment->payment_type == 'cash' && $shipment->payment_status == 2) {
						return back()->with('error', 'Please first complete your payment? go to edit and select payment status paid then update this shipment');
					} elseif (($shipment->payment_type == 'cash' || $shipment->payment_type == 'wallet') && $shipment->payment_status == 1) {
						$this->shipmentStatusUpdate($shipment, 4, 'delivered', $time);
						$transaction = new Transaction();
						$remarks = "The receiver completes the payment for the delivered shipment";
						if ($shipment->shipment_type == 'condition') {
							TransactionService::shipmentTransaction($shipment, $transaction, $trans, $shipment->receiver_branch, optional($shipment->receiver)->id, $remarks, $shipment_type = 'conditional_amount_receive');
							DB::commit();
							NotifyMailService::deliveredConditionalShipment($shipment);
							return back()->with('success', 'Shipment Delivered Successfully!');
						} else {
							TransactionService::shipmentTransaction($shipment, $transaction, $trans, $shipment->receiver_branch, optional($shipment->receiver)->id, $remarks);
							DB::commit();
							NotifyMailService::deliveredShipmentRequest($shipment);
							return back()->with('success', 'Shipment Delivered Successfully!');
						}
					}
				}

				$this->shipmentStatusUpdate($shipment, 4, 'delivered', $time);
				DB::commit();
				if ($shipment->shipment_type == 'condition') {
					$transaction = new Transaction();
					$remarks = "The receiver completes the payment for condition courier amount";
					TransactionService::shipmentTransaction($shipment, $transaction, $trans, $shipment->receiver_branch, optional($shipment->receiver)->id, $remarks, $shipment_type = 'conditional_amount_receive');
					NotifyMailService::deliveredConditionalShipment($shipment);
					return back()->with('success', 'Shipment Delivered Successfully!');
				} else {
					NotifyMailService::deliveredShipmentRequest($shipment);
					return back()->with('success', 'Shipment Delivered Successfully!');
				}

			} elseif ($type == 'return_in_queue') {
				$shipment->status = 8;
				$shipment->save();
				DB::commit();
				return back()->with('success', 'Shipment return back successfully! Now this shipment is in return in queue.');
			} elseif ($type == 'return_in_dispatch') {
				$shipment->status = 9;
				$shipment->save();
				DB::commit();
				return back()->with('success', 'Return Shipment Dispatch successfully! Now this shipment is in return in dispatch.');
			} elseif ($type == 'return_in_received') {
				if ($shipment->return_shipment_cost != null || $shipment->return_shipment_cost != 0) {
					$shipment->payment_status = 2;
					$shipment->payment_type = 'cash';
					$shipment->payment_by = 1;
				}
				$shipment->status = 10;
				$shipment->return_receive_time = Carbon::now();
				$shipment->save();
				DB::commit();
				return back()->with('success', 'Return Shipment Received Successfully!');
			} elseif ($type == 'return_in_delivered') {
				if (($shipment->payment_status == 2) && ($shipment->return_shipment_cost != 0 || $shipment->return_shipment_cost != null)) {
					$shipment->payment_status = 1;
					$transaction = new Transaction();
					TransactionService::returnShipmentDelivered($shipment, $transaction, $trans, optional($shipment->sender)->id);
				}

				$shipment->status = 11;
				$shipment->return_delivered_time = Carbon::now();
				$shipment->save();
				DB::commit();
				return back()->with('success', 'Return Shipment Delivered Successfully!');
			}
		} catch (\Exception $e) {
			DB::rollBack();
			return back()->with('error', $e->getMessage())->withInput();
		}
	}

	public function payConditionShipmentToSender($id)
	{
		$shipment = Shipment::with('senderBranch.branchManager', 'receiverBranch', 'sender', 'receiver')->findOrFail($id);
		$shipment->condition_amount_payment_confirm_to_sender = 1;
		$shipment->condition_payment_time = Carbon::now();
		$shipment->save();
		$transaction = new Transaction();
		$trans = strRandom();
		$remarks = 'Payment complete to sender for this Condition shipment';
		TransactionService::shipmentTransaction($shipment, $transaction, $trans, $shipment->sender_branch, optional($shipment->sender)->id, $remarks, $shipment_type = 'conditional_amount_pay');
		NotifyMailService::conditionShipmentPaymentConfirmToSender($shipment);
		return back()->with('success', 'Condition shipment payment confirm successfully!');
	}

	public function getSelectedBranchSender(Request $request)
	{
		$branchId = $request->branchId;

		$data = User::with('profile')
			->whereHas('profile', function ($query) use ($branchId) {
				$query->where('branch_id', $branchId);
			})
			->where('user_type', 1)
			->latest()
			->get();

		return response($data);
	}

	public function getSelectedBranchReceiver(Request $request)
	{
		$branchId = $request->branchId;

		$data = User::with('profile')
			->whereHas('profile', function ($query) use ($branchId) {
				$query->where('branch_id', $branchId);
			})
			->where('user_type', 2)
			->latest()
			->get();

		return response($data);
	}

}
