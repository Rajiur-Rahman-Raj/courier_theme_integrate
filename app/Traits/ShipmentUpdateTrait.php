<?php

namespace App\Traits;

trait ShipmentUpdateTrait
{
	public function shipmentStatusUpdate($shipment, $status = null, $time_type = null, $time = null){
		$shipment->status = $status;
		if ($time_type == 'dispatch'){
			$shipment->dispatch_time = $time;
		}elseif($time_type == 'received'){
			$shipment->receive_time = $time;
		}elseif ($time_type == 'delivered'){
			$shipment->delivered_time = $time;
		}

		$shipment->save();
	}
}
