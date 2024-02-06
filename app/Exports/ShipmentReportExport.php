<?php

namespace App\Exports;

use App\Models\Shipment;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShipmentReportExport implements FromCollection, WithHeadings
{
	/**
	 * @return \Illuminate\Support\Collection
	 */
	private $request;

	public function __construct($request)
	{
		$this->request = $request;
	}

	public function collection()
	{
		$fromDate = Carbon::parse($this->request->from_date);
		$toDate = Carbon::parse($this->request->to_date)->addDay();
		$search = $this->request;
		$SL = 0;
		return $data['shipmentReports'] = Shipment::with('senderBranch.branchManager', 'senderBranch.branchDriver.admin', 'receiverBranch.branchManager', 'receiverBranch.branchDriver.admin', 'sender', 'receiver', 'fromCountry', 'fromState', 'fromCity', 'fromArea', 'toCountry', 'toState', 'toCity', 'toArea', 'assignToCollect', 'assignToDelivery')
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
			->get()->map(function ($query) use ($SL) {
				$data['SL'] = ++$SL;
				$data['Shipment Id'] = $query->shipment_id;
				$data['Shipment Type'] = $query->shipment_type;
				$data['Shipment Date'] = customDate($query->shipment_date);
				$data['Estimate Delivery Date'] = customDate($query->delivery_date);
				$data['Sender Branch'] = optional($query->senderBranch)->branch_name;
				$data['Receiver Branch'] = optional($query->receiverBranch)->branch_name;
				$data['Sender'] = optional($query->sender)->name;
				$data['Receiver'] = optional($query->receiver)->name;
				$data['From Country'] = optional($query->fromCountry)->name;
				$data['To Country'] = optional($query->toCountry)->name;
				$data['From State'] = optional($query->fromState)->name;
				$data['To State'] = optional($query->toState)->name;
				$data['From City'] = optional($query->fromCity)->name;
				$data['To City'] = optional($query->toCity)->name;
				$data['From Area'] = optional($query->fromArea)->name;
				$data['To Area'] = optional($query->toArea)->name;
				$data['Payment Type'] = $query->payment_type;
				$data['Payment By'] = $query->payment_by == 1 ? 'Sender' : 'Receiver';
				$data['Payment Status'] = $query->payment_status == 1 ? 'Paid' : 'Unpaid';
				if (($query->status == 0) || ($query->status == 5 && $query->assign_to_collect != null)) {
					$data['Shipment Status'] = 'Requested';
				} elseif ($query->status == 6) {
					$data['Shipment Status'] = 'Canceled';
				} elseif ($query->status == 1) {
					$data['Shipment Status'] = 'In Queue';
				} elseif ($query->status == 2) {
					$data['Shipment Status'] = 'Dispatch';
				} elseif ($query->status == 3) {
					$data['Shipment Status'] = 'Received';
				} elseif ($query->status == 4) {
					$data['Shipment Status'] = 'Delivered';
				} elseif ($query->status == 8) {
					$data['Shipment Status'] = 'Return In Queue';
				} elseif ($query->status == 9) {
					$data['Shipment Status'] = 'Return Dispatch';
				} elseif ($query->status == 10) {
					$data['Shipment Status'] = 'Return Received';
				} elseif ($query->status == 11) {
					$data['Shipment Status'] = 'Return Delivered';
				}
				$data['Total Cost'] = config('basic.currency_symbol') . $query->total_pay;
				return $data;
			});
	}

	public function headings(): array
	{
		return [
			'SL',
			'Shipment Id',
			'Shipment Type',
			'Shipment Date',
			'Estimate Delivery Date',
			'Sender Branch',
			'Receiver Branch',
			'Sender',
			'Receiver',
			'From Country',
			'From State',
			'From City',
			'From Area',
			'To Country',
			'To State',
			'To City',
			'To Area',
			'Payment Type',
			'Payment By',
			'Payment Status',
			'Shipment Status',
			'Total Cost',
		];
	}
}
