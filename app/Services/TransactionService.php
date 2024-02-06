<?php

namespace App\Services;
use App\Models\Shipment;
use App\Traits\Notify;
use App\Traits\Upload;

class TransactionService
{
    use Upload, Notify;


	public function shipmentTransaction($shipment, $transaction, $trans = null, $branch = null, $payment_by = null, $remarks=null, $shipment_type = null){
		$transaction->shipment_type = $shipment->shipment_type;
		$transaction->shipment_identifier = $shipment->shipment_identifier;
		$transaction->shipment_id = $shipment->id;
		$transaction->branch_id = $branch;
		$transaction->user_id = $payment_by;
		$transaction->amount = round($shipment->total_pay, 2);
		$transaction->charge = 0;
		$transaction->trx_type = '+';
		$transaction->trx_id = $trans;
		$transaction->remarks = $remarks;
		$transaction->transactional_type = Shipment::class;

		if ($shipment_type == 'conditional_amount_receive'){
			$transaction->condition_receive_amount = round($shipment->receive_amount, 2);
			$transaction->condition_receive_payment_by_receiver_branch = 1;
			$transaction->amount = 0;
		}

		if ($shipment_type == 'conditional_amount_pay'){
			$transaction->condition_receive_amount = round($shipment->receive_amount, 2);
			$transaction->condition_receive_payment_by_receiver_branch = 1;
			$transaction->condition_receive_payment_by_sender_branch = 1;
			$transaction->trx_type = '-';
			$transaction->amount = 0;
		}

		$shipment->transactional()->save($transaction);
	}


	public function requestedShipmentAccept($shipment, $transaction, $trans = null, $payment_by = null){
		$transaction->shipment_type = $shipment->shipment_type;
		$transaction->shipment_identifier = $shipment->shipment_identifier;
		$transaction->shipment_id = $shipment->id;
		$transaction->branch_id = $shipment->sender_branch;
		$transaction->user_id = $payment_by;
		$transaction->amount = round($shipment->total_pay, 2);
		$transaction->charge = 0;
		$transaction->trx_type = '+';
		$transaction->trx_id = $trans;
		$transaction->remarks = 'Accept shipment request & Payment complete for this shipment. ' . 'Shipment Id: '. '('.$shipment->shipment_id.')'. " Now this shipment currently in queue";
		$transaction->transactional_type = Shipment::class;
		$shipment->transactional()->save($transaction);
	}

	public function conditionShipmentPaymentConfirmToSenderBranch($shipment, $transaction, $trans = null, $payment_receive = null){
		$transaction->shipment_id = $shipment->id;
		$transaction->branch_id = $shipment->sender_branch;
		$transaction->user_id = $payment_receive;
		$transaction->condition_receive_amount = round($shipment->receive_amount, 2);
		$transaction->condition_receive_payment_by_receiver_branch = 1;
		$transaction->condition_receive_payment_by_sender_branch = 1;
		$transaction->charge = 0;
		$transaction->trx_type = '-';
		$transaction->trx_id = $trans;
		$transaction->remarks = 'Payment complete for this Condition shipment. ' . 'Shipment Id: '. '('.$shipment->shipment_id.')';
		$transaction->transactional_type = Shipment::class;
		$shipment->transactional()->save($transaction);
	}

	public function returnShipmentDelivered($shipment, $transaction, $trans = null, $payment_by = null){
		$transaction->shipment_id = $shipment->id;
		$transaction->branch_id = $shipment->sender_branch;
		$transaction->user_id = $payment_by;
		$transaction->amount = round($shipment->return_shipment_cost, 2);
		$transaction->charge = 0;
		$transaction->trx_type = '+';
		$transaction->trx_id = $trans;
		$transaction->remarks = 'Return shipment Cost complete ' . 'Shipment Id: '. '('.$shipment->shipment_id.')';
		$transaction->transactional_type = Shipment::class;
		$shipment->transactional()->save($transaction);
	}

}
