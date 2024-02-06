<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\Deposit;
use App\Models\FirebaseNotify;
use App\Models\Payout;
use App\Models\Shipment;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

	public function percentGrowthCalculation($currentRecords, $previousRecords = 0)
	{
		if (0 < $previousRecords) {
			$percentGrowth = (($currentRecords - $previousRecords) / $previousRecords) * 100;
		} else {
			$percentGrowth = 0;
		}

		if ($percentGrowth > 0) {
			$class = "text-success";
			$arrowIcon = "fas fa-arrow-up";
		} elseif ($percentGrowth < 0) {
			$class = "text-danger";
			$arrowIcon = "fas fa-arrow-down";
		} else {
			$class = "text-secondary";
			$arrowIcon = null;
		}

		return [
			'class' => $class,
			'arrowIcon' => $arrowIcon,
			'percentage' => round($percentGrowth, 2)
		];
	}

	public function pushGrowthCalculation($calculationType = null, $records, $percentGrowthCalculation)
	{
		if ($calculationType != null) {
			$records->put('current' . $calculationType . 'Class', $percentGrowthCalculation['class']);
			$records->put('current' . $calculationType . 'ArrowIcon', $percentGrowthCalculation['arrowIcon']);
			$records->put('current' . $calculationType . 'Percentage', $percentGrowthCalculation['percentage']);
		}
	}


	public function getAdminDashboardData()
	{
		$basic = config('basic');
		$basicControl = basicControl();
		$yearLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December'];
		$today = today();
		$branch = \auth()->guard('admin')->user()->branch;

		$tickets = Ticket::selectRaw('COUNT((CASE WHEN status = 0  THEN id END)) AS pendingTickets')
			->selectRaw('COUNT((CASE WHEN status = 1 THEN id END)) AS answeredTickets')
			->selectRaw('COUNT((CASE WHEN status = 2 THEN id END)) AS repliedTickets')
			->selectRaw('COUNT((CASE WHEN status = 2 THEN id END)) AS closedTickets')
			->get()->toArray();

		$data['ticketRecords'] = collect($tickets)->collapse();

		return response()->json(['data' => $data, 'basic' => $basic]);
	}

	public function getUserRecordsData(){
		$basic = config('basic');
		$last30 = date('Y-m-d', strtotime('-30 days'));
		$users = User::selectRaw('COUNT(id) AS totalUser')
			->selectRaw('COUNT((CASE WHEN status = 1  THEN id END)) AS activeUser')
			->selectRaw("COUNT((CASE WHEN MONTH(created_at) = MONTH(CURDATE()) THEN id END)) AS thisMonthUsers")
			->selectRaw("COUNT((CASE WHEN MONTH(created_at) = MONTH(CURDATE()) - 1 THEN id END)) AS lastMonthUsers")
			->selectRaw("COUNT((CASE WHEN MONTH(created_at) = MONTH(CURDATE()) - 2 THEN id END)) AS monthBeforeLastMonthUsers")
			->selectRaw("COUNT((CASE WHEN YEAR(created_at) = YEAR(CURDATE()) THEN id END)) AS thisYearUsers")
			->selectRaw("COUNT((CASE WHEN YEAR(created_at) = YEAR(CURDATE()) - 1 THEN id END)) AS lastYearUsers")
			->selectRaw("COUNT((CASE WHEN created_at >= $last30 THEN id END)) AS last_30_days_join")
			->selectRaw('SUM(balance) AS totalUserBalance')
			->selectRaw('COUNT((CASE WHEN email_verified_at IS NOT NULL  THEN id END)) AS verifiedUser')
			->get()->makeHidden(['mobile', 'profile'])->toArray();

		$data['userRecords'] = collect($users)->collapse();

		$currentMonthUserGrowthCalculation = $this->percentGrowthCalculation($data['userRecords']['thisMonthUsers'], $data['userRecords']['lastMonthUsers']);
		$currentYearUserGrowthCalculation = $this->percentGrowthCalculation($data['userRecords']['thisYearUsers'], $data['userRecords']['lastYearUsers']);

		$this->pushGrowthCalculation('Month', $data['userRecords'], $currentMonthUserGrowthCalculation);
		$this->pushGrowthCalculation('Year', $data['userRecords'], $currentYearUserGrowthCalculation);

		$depositStat = Deposit::selectRaw('SUM((CASE WHEN MONTH(created_at) = MONTH(CURDATE()) THEN amount END)) AS thisMonthDeposit')
			->selectRaw('SUM((CASE WHEN MONTH(created_at) = MONTH(CURDATE()) - 1 THEN amount END)) AS lastMonthDeposit')
			->where('status', 1)
			->get()
			->toArray();

		$data['depositStatRecords'] = collect($depositStat)->collapse();

		$currentMonthDepositGrowthCalculation = $this->percentGrowthCalculation($data['depositStatRecords']['thisMonthDeposit'], $data['depositStatRecords']['lastMonthDeposit']);
		$this->pushGrowthCalculation('Month', $data['depositStatRecords'], $currentMonthDepositGrowthCalculation);

		return response()->json(['data' => $data, 'basic' => $basic]);

	}

	public function getBranchStatRecords(){
		$basic = config('basic');
		$branches = Branch::selectRaw('COUNT(branches.id) AS totalBranches')
			->selectRaw('COUNT(CASE WHEN branches.status = 1 THEN branches.id END) AS totalActiveBranches')
			->selectRaw('COUNT(CASE WHEN branches.status = 0 THEN branches.id END) AS totalInactiveBranches')
			->selectRaw('COUNT(branch_managers.id) AS totalBranchManagers')
			->selectRaw('COUNT(branch_drivers.id) AS totalBranchDrivers')
			->selectRaw('COUNT(branch_employees.id) AS totalBranchEmployees')
			->leftJoin('branch_managers', 'branches.id', '=', 'branch_managers.branch_id')
			->leftJoin('branch_drivers', 'branches.id', '=', 'branch_drivers.branch_id')
			->leftJoin('branch_employees', 'branches.id', '=', 'branch_employees.branch_id')
			->get()
			->toArray();

		$data['branchRecords'] = collect($branches)->collapse();

		return response()->json(['data' => $data, 'basic' => $basic]);

	}

	public function getShipmentStatRecords(){
		$basic = config('basic');
		$branch = \auth()->guard('admin')->user()->branch;
		$shipments = Shipment::selectRaw('COUNT(shipments.id) AS totalShipments')
			->selectRaw('COUNT((CASE WHEN created_at >= CURDATE() THEN id END)) AS totalTodayShipments')
			->selectRaw('COUNT((CASE WHEN created_at >= CURDATE() - 1 THEN id END)) AS totalBeforeTodayShipments')
			->selectRaw("COUNT((CASE WHEN MONTH(created_at) = MONTH(CURDATE()) THEN id END)) AS thisMonthShipments")
			->selectRaw("COUNT((CASE WHEN MONTH(created_at) = MONTH(CURDATE()) - 1 THEN id END)) AS lastMonthShipments")
			->selectRaw("COUNT((CASE WHEN shipments.shipment_identifier = 1 AND MONTH(created_at) = MONTH(CURDATE()) THEN id END)) AS thisMonthOperatorCountryShipments")
			->selectRaw("COUNT((CASE WHEN shipments.shipment_identifier = 1 AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN id END)) AS lastMonthOperatorCountryShipments")
			->selectRaw("COUNT((CASE WHEN shipments.shipment_identifier = 2 AND MONTH(created_at) = MONTH(CURDATE()) THEN id END)) AS thisMonthInternationallyShipments")
			->selectRaw("COUNT((CASE WHEN shipments.shipment_identifier = 2 AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN id END)) AS lastMonthInternationallyShipments")
			->selectRaw("COUNT((CASE WHEN shipments.shipment_type = 'drop_off' AND MONTH(created_at) = MONTH(CURDATE()) THEN id END)) AS thisMonthDropOffShipments")
			->selectRaw("COUNT((CASE WHEN shipments.shipment_type = 'drop_off' AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN id END)) AS lastMonthDropOffShipments")
			->selectRaw("COUNT((CASE WHEN shipments.shipment_type = 'pickup' AND MONTH(created_at) = MONTH(CURDATE()) THEN id END)) AS thisMonthPickupShipments")
			->selectRaw("COUNT((CASE WHEN shipments.shipment_type = 'pickup' AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN id END)) AS lastMonthPickupShipments")
			->selectRaw("COUNT((CASE WHEN shipments.shipment_type = 'condition' AND MONTH(created_at) = MONTH(CURDATE()) THEN id END)) AS thisMonthConditionShipments")
			->selectRaw("COUNT((CASE WHEN shipments.shipment_type = 'condition' AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN id END)) AS lastMonthConditionShipments")
			->when(isset($branch->branch_id), function ($query) use ($branch) {
				return $query->where('sender_branch', $branch->branch_id)->orWhere('receiver_branch', $branch->branch_id);
			})
			->get()
			->toArray();

		$data['shipmentRecords'] = collect($shipments)->collapse();

		$todayShipmentGrowthCalculation = $this->percentGrowthCalculation($data['shipmentRecords']['totalTodayShipments'], $data['shipmentRecords']['totalBeforeTodayShipments']);
		$currentMonthShipmentGrowthCalculation = $this->percentGrowthCalculation($data['shipmentRecords']['thisMonthShipments'], $data['shipmentRecords']['lastMonthShipments']);
		$currentMonthOperatorCountryShipmentGrowthCalculation = $this->percentGrowthCalculation($data['shipmentRecords']['thisMonthOperatorCountryShipments'], $data['shipmentRecords']['lastMonthOperatorCountryShipments']);
		$currentMonthInternationallyShipmentGrowthCalculation = $this->percentGrowthCalculation($data['shipmentRecords']['thisMonthInternationallyShipments'], $data['shipmentRecords']['lastMonthInternationallyShipments']);

		$currentMonthDropOffShipmentGrowthCalculation = $this->percentGrowthCalculation($data['shipmentRecords']['thisMonthDropOffShipments'], $data['shipmentRecords']['lastMonthDropOffShipments']);
		$currentMonthPickupShipmentGrowthCalculation = $this->percentGrowthCalculation($data['shipmentRecords']['thisMonthPickupShipments'], $data['shipmentRecords']['lastMonthPickupShipments']);
		$currentMonthConditionShipmentGrowthCalculation = $this->percentGrowthCalculation($data['shipmentRecords']['thisMonthConditionShipments'], $data['shipmentRecords']['lastMonthConditionShipments']);

		$this->pushGrowthCalculation('Today', $data['shipmentRecords'], $todayShipmentGrowthCalculation);
		$this->pushGrowthCalculation('Month', $data['shipmentRecords'], $currentMonthShipmentGrowthCalculation);
		$this->pushGrowthCalculation('MonthOperatorCountry', $data['shipmentRecords'], $currentMonthOperatorCountryShipmentGrowthCalculation);
		$this->pushGrowthCalculation('MonthInternationally', $data['shipmentRecords'], $currentMonthInternationallyShipmentGrowthCalculation);

		$this->pushGrowthCalculation('MonthDropOff', $data['shipmentRecords'], $currentMonthDropOffShipmentGrowthCalculation);
		$this->pushGrowthCalculation('MonthPickup', $data['shipmentRecords'], $currentMonthPickupShipmentGrowthCalculation);
		$this->pushGrowthCalculation('MonthCondition', $data['shipmentRecords'], $currentMonthConditionShipmentGrowthCalculation);

		return response()->json(['data' => $data, 'basic' => $basic]);
	}

	public function getShipmentTransactionRecords(){
		$basic = config('basic');
		$branch = \auth()->guard('admin')->user()->branch;
		$shipmentTransactions = Transaction::selectRaw('SUM(CASE WHEN shipment_id IS NOT NULL THEN amount ELSE 0 END) AS totalShipmentTransactions')
			->selectRaw('SUM(CASE WHEN shipment_id IS NOT NULL AND created_at >= CURDATE() THEN amount ELSE 0 END) AS totalTodayShipmentTransactions')
			->selectRaw('SUM(CASE WHEN shipment_id IS NOT NULL AND created_at >= CURDATE() -1 THEN amount ELSE 0 END) AS totalBeforeTodayShipmentTransactions')
			->selectRaw('SUM(CASE WHEN shipment_id IS NOT NULL AND MONTH(created_at) = MONTH(CURDATE()) THEN amount ELSE 0 END) AS thisMonthShipmentTransactions')
			->selectRaw('SUM(CASE WHEN shipment_id IS NOT NULL AND MONTH(created_at) = MONTH(CURDATE()) -1 THEN amount ELSE 0 END) AS lastMonthShipmentTransactions')
			->selectRaw('SUM(CASE WHEN shipment_identifier IS NOT NULL AND shipment_identifier = 1 AND MONTH(created_at) = MONTH(CURDATE()) THEN amount ELSE 0 END) AS thisMonthOperatorCountryTransactions')
			->selectRaw('SUM(CASE WHEN shipment_identifier IS NOT NULL AND shipment_identifier = 1 AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN amount ELSE 0 END) AS lastMonthOperatorCountryTransactions')
			->selectRaw('SUM(CASE WHEN shipment_identifier IS NOT NULL AND shipment_identifier = 2 AND MONTH(created_at) = MONTH(CURDATE()) THEN amount ELSE 0 END) AS thisMonthInternationallyTransactions')
			->selectRaw('SUM(CASE WHEN shipment_identifier IS NOT NULL AND shipment_identifier = 2 AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN amount ELSE 0 END) AS lastMonthInternationallyTransactions')
			->selectRaw('SUM(CASE WHEN shipment_type IS NOT NULL AND shipment_type = "drop_off" THEN amount ELSE 0 END) AS totalDropOffTransactions')
			->selectRaw('SUM(CASE WHEN shipment_type IS NOT NULL AND shipment_type = "pickup" THEN amount ELSE 0 END) AS totalPickupTransactions')
			->selectRaw('SUM(CASE WHEN shipment_type IS NOT NULL AND shipment_type = "condition" THEN amount ELSE 0 END) AS totalConditionTransactions')
			->get()
			->toArray();


		$data['transactionRecords'] = collect($shipmentTransactions)->collapse();

		$todayShipmentTransactionGrowthCalculation = $this->percentGrowthCalculation($data['transactionRecords']['totalTodayShipmentTransactions'], $data['transactionRecords']['totalBeforeTodayShipmentTransactions']);
		$currentMonthTransactionShipmentGrowthCalculation = $this->percentGrowthCalculation($data['transactionRecords']['thisMonthShipmentTransactions'], $data['transactionRecords']['lastMonthShipmentTransactions']);

		$currentMonthOperatorCountryTransactionShipmentGrowthCalculation = $this->percentGrowthCalculation($data['transactionRecords']['thisMonthOperatorCountryTransactions'], $data['transactionRecords']['lastMonthOperatorCountryTransactions']);
		$currentMonthInternationallyTransactionShipmentGrowthCalculation = $this->percentGrowthCalculation($data['transactionRecords']['thisMonthInternationallyTransactions'], $data['transactionRecords']['lastMonthInternationallyTransactions']);

		$this->pushGrowthCalculation('Today', $data['transactionRecords'], $todayShipmentTransactionGrowthCalculation);
		$this->pushGrowthCalculation('Month', $data['transactionRecords'], $currentMonthTransactionShipmentGrowthCalculation);

		$this->pushGrowthCalculation('MonthOperatorCountry', $data['transactionRecords'], $currentMonthOperatorCountryTransactionShipmentGrowthCalculation);
		$this->pushGrowthCalculation('MonthInternationally', $data['transactionRecords'], $currentMonthInternationallyTransactionShipmentGrowthCalculation);

		return response()->json(['data' => $data, 'basic' => $basic]);
	}

	public function getYearShipmentChartRecords(){
		$basic = config('basic');
		$branch = \auth()->guard('admin')->user()->branch;
		$yearLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December'];
		$today = today();

		$monthlyShipments = Shipment::select('created_at')
			->whereYear('created_at', $today)
			->groupBy([DB::raw("DATE_FORMAT(created_at, '%m')")])
			->selectRaw('COUNT(shipments.id) AS totalShipments')
			->selectRaw('COUNT(CASE WHEN shipments.shipment_identifier = 1 THEN shipments.id END) AS totalOperatorCountryShipments')
			->selectRaw('COUNT(CASE WHEN shipments.shipment_identifier = 2 THEN shipments.id END) AS totalInternationallyShipments')
			->selectRaw('COUNT(CASE WHEN shipments.shipment_type = "drop_off" THEN shipments.id END) AS totalDropOffShipments')
			->selectRaw('COUNT(CASE WHEN shipments.shipment_type = "pickup" THEN shipments.id END) AS totalPickupShipments')
			->selectRaw('COUNT(CASE WHEN shipments.shipment_type = "condition" THEN shipments.id END) AS totalConditionShipments')
			->selectRaw('COUNT(CASE WHEN shipments.status = 0 THEN shipments.id END) AS totalPendingShipments')
			->selectRaw('COUNT(CASE WHEN shipments.status = 4 THEN shipments.id END) AS totalDeliveredShipments')
			->selectRaw('COUNT(CASE WHEN shipments.status = 11 THEN shipments.id END) AS totalReturnInDelivered')
			->when(isset($branch->branch_id), function ($query) use ($branch) {
				return $query->where('sender_branch', $branch->branch_id)->orWhere('receiver_branch', $branch->branch_id);
			})
			->get()
			->groupBy([function ($query) {
				return $query->created_at->format('F');
			}]);


		$yearTotalShipments = [];
		$yearOperatorCountryShipments = [];
		$yearInternationallyShipments = [];
		$yearDropOffShipments = [];
		$yearPickupShipments = [];
		$yearConditionShipments = [];
		$yearRequestShipments = [];
		$yearDeliveredShipments = [];
		$yearReturnShipments = [];


		foreach ($yearLabels as $yearLabel) {
			$currentTotalShipments = 0;
			$currentOperatorCountryShipments = 0;
			$currentInternationallyShipments = 0;
			$currentDropOffShipments = 0;
			$currentPickupShipments = 0;
			$currentConditionShipments = 0;
			$currentRequestShipments = 0;
			$currentDeliveredShipments = 0;
			$currentReturnShipments = 0;

			if (isset($monthlyShipments[$yearLabel])) {
				foreach ($monthlyShipments[$yearLabel] as $key => $shipment) {
					$currentTotalShipments += $shipment->totalShipments;
					$currentOperatorCountryShipments += $shipment->totalOperatorCountryShipments;
					$currentInternationallyShipments += $shipment->totalInternationallyShipments;
					$currentDropOffShipments += $shipment->totalDropOffShipments;
					$currentPickupShipments += $shipment->totalPickupShipments;
					$currentConditionShipments += $shipment->totalConditionShipments;
					$currentRequestShipments += $shipment->totalPendingShipments;
					$currentDeliveredShipments += $shipment->totalDeliveredShipments;
					$currentReturnShipments += $shipment->totalReturnInDelivered;
				}
			}

			$yearTotalShipments[] = $currentTotalShipments;
			$yearOperatorCountryShipments[] = $currentOperatorCountryShipments;
			$yearInternationallyShipments[] = $currentInternationallyShipments;
			$yearDropOffShipments[] = $currentDropOffShipments;
			$yearPickupShipments[] = $currentPickupShipments;
			$yearConditionShipments[] = $currentConditionShipments;
			$yearRequestShipments[] = $currentRequestShipments;
			$yearDeliveredShipments[] = $currentDeliveredShipments;
			$yearReturnShipments[] = $currentReturnShipments;
		}

		$data['yearShipmentChartRecords'] = [
			'shipmentYearLabels' => $yearLabels,
			'yearTotalShipments' => $yearTotalShipments,
			'yearOperatorCountryShipments' => $yearOperatorCountryShipments,
			'yearInternationallyShipments' => $yearInternationallyShipments,
			'yearDropOffShipments' => $yearDropOffShipments,
			'yearPickupShipments' => $yearPickupShipments,
			'yearConditionShipments' => $yearConditionShipments,
			'yearRequestShipments' => $yearRequestShipments,
			'yearDeliveredShipments' => $yearDeliveredShipments,
			'yearReturnShipments' => $yearReturnShipments,
		];

		return response()->json(['data' => $data, 'basic' => $basic]);
	}

	public function getYearShipmentTransactionChartRecords(){
		$basic = config('basic');
		$branch = \auth()->guard('admin')->user()->branch;
		$yearLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December'];
		$today = today();

		$monthlyShipmentTransactions = Transaction::select('created_at')
			->whereYear('created_at', $today)
			->groupBy([DB::raw("DATE_FORMAT(created_at, '%m')")])
			->selectRaw('SUM(CASE WHEN shipment_id IS NOT NULL THEN amount ELSE 0 END) AS totalShipmentTransactions')
			->selectRaw('SUM(CASE WHEN shipment_identifier IS NOT NULL AND shipment_identifier = 1 THEN amount ELSE 0 END) AS totalOperatorCountryTransactions')
			->selectRaw('SUM(CASE WHEN shipment_identifier IS NOT NULL AND shipment_identifier = 2 THEN amount ELSE 0 END) AS totalInternationallyTransactions')
			->selectRaw('SUM(CASE WHEN shipment_type IS NOT NULL AND shipment_type = "drop_off" THEN amount ELSE 0 END) AS totalDropOffTransactions')
			->selectRaw('SUM(CASE WHEN shipment_type IS NOT NULL AND shipment_type = "pickup" THEN amount ELSE 0 END) AS totalPickupTransactions')
			->selectRaw('SUM(CASE WHEN shipment_type IS NOT NULL AND shipment_type = "condition" THEN amount ELSE 0 END) AS totalConditionTransactions')
			->when(isset($branch->branch_id), function ($query) use ($branch) {
				return $query->where('branch_id', $branch->branch_id);
			})
			->get()
			->groupBy([function ($query) {
				return $query->created_at->format('F');
			}]);


		$yearTotalShipmentTransactions = [];
		$yearTotalOperatorCountryTransactions = [];
		$yearTotalInternationallyTransactions = [];
		$yearTotalDropOffTransactions = [];
		$yearTotalPickupTransactions = [];
		$yearTotalConditionTransactions = [];

		foreach ($yearLabels as $yearLabel) {
			$currentTotalShipmentTransactions = 0;
			$currentTotalOperatorCountryTransactions = 0;
			$currentTotalInternationallyTransactions = 0;
			$currentTotalDropOffTransactions = 0;
			$currentTotalPickupTransactions = 0;
			$currentTotalConditionTransactions = 0;

			if (isset($monthlyShipmentTransactions[$yearLabel])) {
				foreach ($monthlyShipmentTransactions[$yearLabel] as $key => $shipment) {
					$currentTotalShipmentTransactions += $shipment->totalShipmentTransactions;
					$currentTotalOperatorCountryTransactions += $shipment->totalOperatorCountryTransactions;
					$currentTotalInternationallyTransactions += $shipment->totalInternationallyTransactions;
					$currentTotalDropOffTransactions += $shipment->totalDropOffTransactions;
					$currentTotalPickupTransactions += $shipment->totalPickupTransactions;
					$currentTotalConditionTransactions += $shipment->totalConditionTransactions;
				}
			}

			$yearTotalShipmentTransactions[] = $currentTotalShipmentTransactions;
			$yearTotalOperatorCountryTransactions[] = $currentTotalOperatorCountryTransactions;
			$yearTotalInternationallyTransactions[] = $currentTotalInternationallyTransactions;
			$yearTotalDropOffTransactions[] = $currentTotalDropOffTransactions;
			$yearTotalPickupTransactions[] = $currentTotalPickupTransactions;
			$yearTotalConditionTransactions[] = $currentTotalConditionTransactions;
		}

		$data['yearShipmentTransactionChartRecords'] = [
			'yearLabels' => $yearLabels,
			'yearTotalShipmentTransactions' => $yearTotalShipmentTransactions,
			'yearTotalOperatorCountryTransactions' => $yearTotalOperatorCountryTransactions,
			'yearTotalInternationallyTransactions' => $yearTotalInternationallyTransactions,
			'yearTotalDropOffTransactions' => $yearTotalDropOffTransactions,
			'yearTotalPickupTransactions' => $yearTotalPickupTransactions,
			'yearTotalConditionTransactions' => $yearTotalConditionTransactions,
		];

		return response()->json(['data' => $data, 'basic' => $basic]);
	}


	public function getDepositPayoutChartRecords(){
		$basic = config('basic');
		$basicControl = basicControl();
		$branch = \auth()->guard('admin')->user()->branch;
		$yearLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December'];
		$today = today();

		$deposits = Deposit::select('created_at')
			->where('status', 1)
			->whereYear('created_at', $today)
			->groupBy([DB::raw("DATE_FORMAT(created_at, '%m')")])
			->selectRaw("SUM(amount) as Deposit")
			->get()
			->groupBy([function ($query) {
				return $query->created_at->format('F');
			}]);

		$payouts = Payout::select('created_at')
			->whereYear('created_at', $today)
			->groupBy([DB::raw("DATE_FORMAT(created_at, '%m')")])
			->selectRaw("SUM(amount) as Payout")
			->get()
			->groupBy([function ($query) {
				return $query->created_at->format('F');
			}]);

		$yearDeposit = [];
		$yearPayout = [];

		foreach ($yearLabels as $yearLabel) {
			$currentYearDeposit = 0;
			$currentYearPayout = 0;

			if (isset($deposits[$yearLabel])) {
				foreach ($deposits[$yearLabel] as $key => $deposit) {
					$currentYearDeposit += $deposit->Deposit;
				}
			}
			if (isset($payouts[$yearLabel])) {
				foreach ($payouts[$yearLabel] as $key => $payout) {
					$currentYearPayout += $payout->Payout;
				}
			}

			$yearDeposit[] = round($currentYearDeposit, $basicControl->fraction_number);
			$yearPayout[] = round($currentYearPayout, $basicControl->fraction_number);
		}

		$data['yearDepositPayoutChartRecords'] = [
			'yearLabels' => $yearLabels,
			'yearDeposit' => $yearDeposit,
			'yearPayout' => $yearPayout,
		];


		$paymentMethods = Deposit::with('gateway:id,name')
			->whereYear('created_at', $today)
			->where('status', 1)
			->groupBy(['payment_method_id'])
			->selectRaw("SUM(amount) as totalAmount, payment_method_id")
			->get()
			->groupBy(['payment_method_id']);

		$paymentMethodeLabel = [];
		$paymentMethodeData = [];

		$paymentMethods = collect($paymentMethods)->collapse();
		foreach ($paymentMethods as $paymentMethode) {
			$currentPaymentMethodeLabel = 0;
			$currentPaymentMethodeData = 0;
			$currentPaymentMethodeLabel = optional($paymentMethode->gateway)->name ?? 'N/A';
			$currentPaymentMethodeData += $paymentMethode->totalAmount;

			$paymentMethodeLabel[] = $currentPaymentMethodeLabel;
			$paymentMethodeData[] = round($currentPaymentMethodeData, $basicControl->fraction_number);
		}

		$data['yearDepositSummeryChartRecords'] = [
			'paymentMethodeLabel' => $paymentMethodeLabel,
			'paymentMethodeData' => $paymentMethodeData,
			'basicControl' => $basicControl,
		];

		return response()->json(['data' => $data, 'basic' => $basic]);
	}


	public function index()
	{

		$basicControl = basicControl();

		$data['basicControl'] = $basicControl;

		$data['firebaseNotify'] = FirebaseNotify::first();

		if (adminAccessRoute(array_merge(config('permissionList.Admin_Dashboard.Dashboard.permission.view')))){
			return view('admin.home', $data);
		}else{
			return redirect()->route('shipmentList', ['shipment_status' => 'assign_to_collect', 'shipment_type' => 'operator-country']);
		}
	}

	public function changePassword(Request $request)
	{
		if ($request->isMethod('get')) {
			return view('admin.auth.passwords.change');
		} elseif ($request->isMethod('post')) {
			$purifiedData = Purify::clean($request->all());
			$validator = Validator::make($purifiedData, [
				'current_password' => 'required|min:5',
				'password' => 'required|min:5|confirmed',
			]);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
			$user = Auth::user();
			$purifiedData = (object)$purifiedData;

			if (!Hash::check($purifiedData->current_password, $user->password)) {
				return back()->withInput()->withErrors(['current_password' => 'current password did not match']);
			}

			$user->password = bcrypt($purifiedData->password);
			$user->save();
			return back()->with('success', 'Password changed successfully');
		}
	}


	public function forbidden()
	{
		return view('admin.errors.403');
	}


	public function getDailyShipmentAnalytics(Request $request)
	{
		$start = Carbon::createFromFormat('d/m/Y', $request->start);
		$end = Carbon::createFromFormat('d/m/Y', $request->end);

		$dailyShipments = DB::table('shipments')
			->selectRaw('DATE(created_at) as date, COUNT(CASE WHEN shipments.status = 0 THEN shipments.id END) AS totalPendingShipments')
			->selectRaw('DATE(created_at) as date, COUNT(CASE WHEN shipments.status = 1 THEN shipments.id END) AS totalInQueueShipments')
			->selectRaw('DATE(created_at) as date, COUNT(CASE WHEN shipments.status = 2 THEN shipments.id END) AS totalDispatchShipments')
			->selectRaw('DATE(created_at) as date, COUNT(CASE WHEN shipments.status = 3 THEN shipments.id END) AS totalReceivedShipments')
			->selectRaw('DATE(created_at) as date, COUNT(CASE WHEN shipments.status = 4 THEN shipments.id END) AS totalDeliveredShipments')
			->selectRaw('DATE(created_at) as date, COUNT(CASE WHEN shipments.status = 8 THEN shipments.id END) AS totalReturnInQueueShipments')
			->selectRaw('DATE(created_at) as date, COUNT(CASE WHEN shipments.status = 9 THEN shipments.id END) AS totalReturnDispatchShipments')
			->selectRaw('DATE(created_at) as date, COUNT(CASE WHEN shipments.status = 10 THEN shipments.id END) AS totalReturnReceivedShipments')
			->selectRaw('DATE(created_at) as date, COUNT(CASE WHEN shipments.status = 11 THEN shipments.id END) AS totalReturnDeliveredShipments')
			->whereBetween('created_at', [$start, $end])
			->groupBy('date')
			->get();

		$start = new \DateTime($start);
		$end = new \DateTime($end);
		$data = [];

		for ($day = $start; $day <= $end; $day->modify('+1 day')) {
			$date = $day->format('Y-m-d');
			$data['labels'][] = $day->format('jS M');
			$data['dataPendingShipment'][] = $dailyShipments->where('date', $date)->first()->totalPendingShipments ?? 0;
			$data['dataInQueueShipment'][] = $dailyShipments->where('date', $date)->first()->totalInQueueShipments ?? 0;
			$data['dataDispatchShipment'][] = $dailyShipments->where('date', $date)->first()->totalDispatchShipments ?? 0;
			$data['dataReceivedShipment'][] = $dailyShipments->where('date', $date)->first()->totalReceivedShipments ?? 0;
			$data['dataDeliveredShipment'][] = $dailyShipments->where('date', $date)->first()->totalDeliveredShipments ?? 0;
			$data['dataReturnInQueueShipment'][] = $dailyShipments->where('date', $date)->first()->totalReturnInQueueShipments ?? 0;
			$data['dataReturnDispatchShipment'][] = $dailyShipments->where('date', $date)->first()->totalReturnDispatchShipments ?? 0;
			$data['dataReturnReceivedShipment'][] = $dailyShipments->where('date', $date)->first()->totalReturnReceivedShipments ?? 0;
			$data['dataReturnDeliveredShipment'][] = $dailyShipments->where('date', $date)->first()->totalReturnDeliveredShipments ?? 0;
		}

		return response()->json($data);
	}


	public function getDailyShipmentTransactionsAnalytics(Request $request)
	{
		$start = Carbon::createFromFormat('d/m/Y', $request->start);
		$end = Carbon::createFromFormat('d/m/Y', $request->end);


		$dailyShipmentTransactions = DB::table('transactions')
			->selectRaw('DATE(created_at) as date, SUM(CASE WHEN shipment_id IS NOT NULL THEN amount ELSE 0 END) AS totalShipmentTransactions')
			->selectRaw('DATE(created_at) as date, SUM(CASE WHEN shipment_identifier IS NOT NULL AND shipment_identifier = 1 THEN amount ELSE 0 END) AS totalOperatorCountryTransactions')
			->selectRaw('DATE(created_at) as date, SUM(CASE WHEN shipment_identifier IS NOT NULL AND shipment_identifier = 2 THEN amount ELSE 0 END) AS totalInternationallyTransactions')
			->selectRaw('DATE(created_at) as date, SUM(CASE WHEN shipment_type IS NOT NULL AND shipment_type = "drop_off" THEN amount ELSE 0 END) AS totalDropOffTransactions')
			->selectRaw('DATE(created_at) as date, SUM(CASE WHEN shipment_type IS NOT NULL AND shipment_type = "pickup" THEN amount ELSE 0 END) AS totalPickupTransactions')
			->selectRaw('DATE(created_at) as date, SUM(CASE WHEN shipment_type IS NOT NULL AND shipment_type = "condition" THEN amount ELSE 0 END) AS totalConditionTransactions')
			->whereBetween('created_at', [$start, $end])
			->groupBy('date')
			->get();

		$start = new \DateTime($start);
		$end = new \DateTime($end);
		$data = [];

		for ($day = $start; $day <= $end; $day->modify('+1 day')) {
			$date = $day->format('Y-m-d');
			$data['labels'][] = $day->format('jS M');
			$data['dataTotalShipmentTransactions'][] = $dailyShipmentTransactions->where('date', $date)->first()->totalShipmentTransactions ?? 0;
			$data['dataTotalOperatorCountryTransactions'][] = $dailyShipmentTransactions->where('date', $date)->first()->totalOperatorCountryTransactions ?? 0;
			$data['dataTotalInternationallyTransactions'][] = $dailyShipmentTransactions->where('date', $date)->first()->totalInternationallyTransactions ?? 0;
			$data['dataDropOffTransactions'][] = $dailyShipmentTransactions->where('date', $date)->first()->totalDropOffTransactions ?? 0;
			$data['dataPickupTransactions'][] = $dailyShipmentTransactions->where('date', $date)->first()->totalPickupTransactions ?? 0;
			$data['dataConditionTransactions'][] = $dailyShipmentTransactions->where('date', $date)->first()->totalConditionTransactions ?? 0;
		}

		return response()->json($data);
	}


	public function getDailyBrowserHistoryAnalytics(Request $request)
	{
		$start = Carbon::createFromFormat('d/m/Y', $request->start);
		$end = Carbon::createFromFormat('d/m/Y', $request->end);

		$userCreationData = DB::table('users')
			->whereBetween('created_at', [$start, $end])
			->select('browser_history')
			->get();

		$data['userCreationBrowserData'] = $userCreationData->groupBy('browser_history')->map->count();

		return response()->json(['userCreationBrowserData' => $data['userCreationBrowserData']]);
	}

	public function getDailyOperatingSystemHistoryAnalytics(Request $request)
	{
		$start = Carbon::createFromFormat('d/m/Y', $request->start);
		$end = Carbon::createFromFormat('d/m/Y', $request->end);

		$userCreationData = DB::table('users')
			->whereBetween('created_at', [$start, $end])
			->select('os_history')
			->get();

		$data['userCreationOSData'] = $userCreationData->groupBy('os_history')->map->count();

		return response()->json(['userCreationOSData' => $data['userCreationOSData']]);
	}

	public function getDailyDeviceHistoryAnalytics(Request $request)
	{
		$start = Carbon::createFromFormat('d/m/Y', $request->start);
		$end = Carbon::createFromFormat('d/m/Y', $request->end);

		$userCreationData = DB::table('users')
			->whereBetween('created_at', [$start, $end])
			->select('device_history')
			->get();

		$data['userCreationDeviceData'] = $userCreationData->groupBy('device_history')->map->count();

		return response()->json(['userCreationDeviceData' => $data['userCreationDeviceData']]);
	}

	public function saveToken(Request $request)
	{
		$admin = auth()->user();
		$admin->fcm_token = $request->token;
		$admin->save();
		return response()->json(['token saved successfully.']);
	}

}
