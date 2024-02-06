<?php

namespace App\Exports;

use App\Models\Shipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShipmentReportCountExport implements FromCollection, WithHeadings
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

		return Shipment::when(isset($search['from_date']), function ($query) use ($fromDate) {
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
			->get()->map(function ($query) {
				$data['Total Shipments'] = $query->total_shipments;
				$data['Total Operator Country Shipments'] = $query->totalOperatorCountryShipments;
				$data['Total Internationally Shipments'] = $query->totalInternationallyShipments;
				$data['Total DropOff Shipments'] = $query->totalDropOffShipments;
				$data['Total Pickup Shipments'] = $query->totalPickupShipments;
				$data['Total Condition Shipments'] = $query->totalConditionShipments;
				$data['Total Pending Shipments'] = $query->totalPendingShipments;
				$data['Total InQueue Shipments'] = $query->totalInQueueShipments;
				$data['Total Dispatch Shipments'] = $query->totalDispatchShipments;
				$data['Total Delivery InQueue Shipments'] = $query->totalDeliveryInQueueShipments;
				$data['Total Delivered Shipments'] = $query->totalDeliveredShipments;
				$data['Total Return InQueue Shipments'] = $query->totalReturnInQueueShipments;
				$data['Total Return InDispatch Shipments'] = $query->totalReturnInDispatchShipments;
				$data['Total Return Delivery InQueue Shipments'] = $query->totalReturnDeliveryInQueueShipments;
				$data['Total Return InDelivered'] = $query->totalReturnInDelivered;
				return $data;
			});
	}

	public function headings(): array
	{
		return [
			'Total Shipments',
			'Total Operator Country Shipments',
			'Total Internationally Shipments',
			'Total DropOff Shipments',
			'Total Pickup Shipments',
			'Total Condition Shipments',
			'Total Pending Shipments',
			'Total InQueue Shipments',
			'Total Dispatch Shipments',
			'Total Delivery InQueue Shipments',
			'Total Delivered Shipments',
			'Total Return InQueue Shipments',
			'Total Return InDispatch Shipments',
			'Total Return Delivery InQueue Shipments',
			'Total Return InDelivered',
		];
	}
}
