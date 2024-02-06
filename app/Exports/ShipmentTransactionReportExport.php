<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShipmentTransactionReportExport implements FromCollection, WithHeadings
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

		return Transaction::when(isset($search['from_date']), function ($query) use ($fromDate) {
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
			->get()->map(function ($query) {
				$data['Total Shipment Transactions'] = $query->totalShipmentTransactions;
				$data['Total Operator Country Transactions'] = $query->totalOperatorCountryTransactions;
				$data['Total Internationally Transactions'] = $query->totalInternationallyTransactions;
				$data['Total Drop Off Transactions'] = $query->totalDropOffTransactions;
				$data['Total Pickup Transactions'] = $query->totalPickupTransactions;
				$data['Total Condition Transactions'] = $query->totalConditionTransactions;
				return $data;
			});
	}


	public function headings(): array
	{
		return [
			'Total Shipment Transactions',
			'Total Operator Country Transactions',
			'Total Internationally Transactions',
			'Total Drop Off Transactions',
			'Total Pickup Transactions',
			'Total Condition Transactions',
		];
	}
}
