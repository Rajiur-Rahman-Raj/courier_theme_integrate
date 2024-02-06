<?php

namespace App\Services;
use App\Traits\Notify;
use App\Traits\Upload;


class NotifyMailService
{
    use Upload, Notify;

	public function getShipmentType($shipment){
		if ($shipment->shipment_identifier == 1){
			$shipment_type = 'operator-country';
		}else{
			$shipment_type = 'internationally';
		}

		return $shipment_type;
	}

	public function getSenderBranchManager($shipment){
		return optional(optional($shipment->senderBranch)->branchManager)->admin;
	}

	public function getReceiverBranchManager($shipment){
		return optional(optional($shipment->receiverBranch)->branchManager)->admin;
	}

	public function acceptShipmentRequestNotify($shipment, $trans = null){

		$params = [
			'sender' => optional($shipment->sender)->name,
			'shipmentId' => $shipment->shipment_id,
			'amount' => getAmount($shipment->total_pay),
			'currency' => config('basic.currency_symbol'),
			'transaction' => $trans,
		];

		$adminAction = [
			"link" => route('shipmentList', ['shipment_status' => 'in_queue', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$this->adminPushNotification($this->getSenderBranchManager($shipment),'ADMIN_NOTIFY_SHIPMENT_REQUEST_ACCEPT', $params, $adminAction, $superAdmin = 1);
		$this->adminFirebasePushNotification($this->getSenderBranchManager($shipment), 'ADMIN_NOTIFY_SHIPMENT_REQUEST_ACCEPT', $params, $adminAction, $superAdmin = 1);
		$this->adminMail($this->getSenderBranchManager($shipment), 'ADMIN_MAIL_SHIPMENT_REQUEST_ACCEPT', $params, $subject = null, $requestMessage = null, $superAdmin = 1);

		$userAction = [
			"link" => route('user.shipmentList', ['shipment_status' => 'in_queue', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fal fa-truck text-white"
		];

		$this->userPushNotification($shipment->sender, 'USER_NOTIFY_SHIPMENT_REQUEST_ACCEPT', $params, $userAction);
		$this->userFirebasePushNotification($shipment->sender, 'USER_NOTIFY_SHIPMENT_REQUEST_ACCEPT', $params, $userAction);
		$this->sendMailSms($shipment->sender, 'USER_MAIL_SHIPMENT_REQUEST_ACCEPT', $params);
	}

	public function cancelShipmentRequestNotify($shipment, $refund_time, $refund_time_type){

		$userAction = [
			"link" => route('user.shipmentList', ['shipment_status' => 'requested', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$adminAction = [
			"link" => route('shipmentList', ['shipment_status' => 'requested', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		if ($shipment->payment_type == 'wallet' && $shipment->payment_status == 1){
			$params = [
				'sender' => optional($shipment->sender)->name,
				'shipmentId' => $shipment->shipment_id,
				'amount' => getAmount($shipment->total_pay),
				'currency' => config('basic.currency_symbol'),
				'refund_time' => $refund_time,
				'refund_time_type' => $refund_time_type
			];
			$this->userPushNotification($shipment->sender, 'USER_NOTIFY_SHIPMENT_REQUEST_CANCEL_WITH_REFUND', $params, $userAction);
			$this->userFirebasePushNotification($shipment->sender, 'USER_NOTIFY_SHIPMENT_REQUEST_CANCEL_WITH_REFUND', $params, $userAction);
			$this->sendMailSms($shipment->sender, 'USER_MAIL_SHIPMENT_REQUEST_CANCEL_WITH_REFUND', $params);

			$this->adminPushNotification($this->getSenderBranchManager($shipment),'ADMIN_NOTIFY_SHIPMENT_REQUEST_CANCEL_WITH_REFUND', $params, $adminAction, $superAdmin = 1);
			$this->adminFirebasePushNotification($this->getSenderBranchManager($shipment), 'ADMIN_NOTIFY_SHIPMENT_REQUEST_CANCEL_WITH_REFUND', $params, $adminAction, $superAdmin = 1);
			$this->adminMail($this->getSenderBranchManager($shipment), 'ADMIN_MAIL_SHIPMENT_REQUEST_CANCEL_WITH_REFUND', $params, $subject = null, $requestMessage = null, $superAdmin = 1);
		}else{
			$params = [
				'sender' => optional($shipment->sender)->name,
				'shipmentId' => $shipment->shipment_id,
			];
			$this->userPushNotification($shipment->sender, 'USER_NOTIFY_SHIPMENT_REQUEST_CANCEL', $params, $userAction);
			$this->userFirebasePushNotification($shipment->sender, 'USER_NOTIFY_SHIPMENT_REQUEST_CANCEL', $params, $userAction);
			$this->sendMailSms($shipment->sender, 'USER_MAIL_SHIPMENT_REQUEST_CANCEL', $params);

			$this->adminPushNotification($this->getSenderBranchManager($shipment),'ADMIN_NOTIFY_SHIPMENT_REQUEST_CANCEL', $params, $adminAction, $superAdmin = 1);
			$this->adminFirebasePushNotification($this->getSenderBranchManager($shipment), 'ADMIN_NOTIFY_SHIPMENT_REQUEST_CANCEL', $params, $adminAction, $superAdmin = 1);
			$this->adminMail($this->getSenderBranchManager($shipment), 'ADMIN_MAIL_SHIPMENT_REQUEST_CANCEL', $params, $subject = null, $requestMessage = null, $superAdmin = 1);
		}
	}

	public function cancelShipmentRequestRefundMoney($shipment, $user, $refundAmount){
		$params = [
			'user' => $user->name,
			'shipmentId' => $shipment->shipment_id,
			'refundAmount' => getAmount($refundAmount),
			'currency' => config('basic.currency_symbol'),
		];

		$adminAction = [
			"link" => route('shipmentList', ['shipment_status' => 'requested', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$this->adminPushNotification($this->getSenderBranchManager($shipment),'ADMIN_NOTIFY_SHIPMENT_REQUEST_CANCEL_REFUND_MONEY', $params, $adminAction, $superAdmin = 1);
		$this->adminFirebasePushNotification($this->getSenderBranchManager($shipment), 'ADMIN_NOTIFY_SHIPMENT_REQUEST_CANCEL_REFUND_MONEY', $params, $adminAction, $superAdmin = 1);
		$this->adminMail($this->getSenderBranchManager($shipment), 'ADMIN_MAIL_SHIPMENT_REQUEST_CANCEL_REFUND_MONEY', $params, $subject = null, $requestMessage = null, $superAdmin = 1);


		$userAction = [
			"link" => route('user.shipmentList', ['shipment_status' => 'requested', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fal fa-truck text-white"
		];

		$this->userPushNotification($shipment->sender, 'USER_NOTIFY_SHIPMENT_REQUEST_CANCEL_REFUND_MONEY', $params, $userAction);
		$this->userFirebasePushNotification($shipment->sender, 'USER_NOTIFY_SHIPMENT_REQUEST_CANCEL_REFUND_MONEY', $params, $userAction);
		$this->sendMailSms($shipment->sender, 'USER_MAIL_SHIPMENT_REQUEST_CANCEL_REFUND_MONEY', $params);
	}

	public function dispatchShipmentRequest($shipment){
		$senderParams = [
			'sender' => optional($shipment->sender)->name,
			'shipmentId' => $shipment->shipment_id,
			'dispatchTime' => $shipment->dispatch_time,
		];

		$receiverParams = [
			'receiver' => optional($shipment->receiver)->name,
			'shipmentId' => $shipment->shipment_id,
			'dispatchTime' => $shipment->dispatch_time,
		];

		$receiverBranchParams = [
			'senderBranch' => optional($shipment->senderBranch)->branch_name,
			'receiverBranch' => optional($shipment->receiverBranch)->branch_name,
			'shipmentId' => $shipment->shipment_id,
			'dispatchTime' => $shipment->dispatch_time,
		];

		$adminAction = [
			"link" => route('shipmentList', ['shipment_status' => 'dispatch', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$receiverBranchAction = [
			"link" => route('shipmentList', ['shipment_status' => 'upcoming', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$this->adminPushNotification($this->getSenderBranchManager($shipment),'ADMIN_NOTIFY_SHIPMENT_DISPATCH', $senderParams, $adminAction, $superAdmin = 1);
		$this->adminFirebasePushNotification($this->getSenderBranchManager($shipment), 'ADMIN_NOTIFY_SHIPMENT_DISPATCH', $senderParams, $adminAction, $superAdmin = 1);
		$this->adminMail($this->getSenderBranchManager($shipment), 'ADMIN_MAIL_SHIPMENT_DISPATCH', $senderParams, $subject = null, $requestMessage = null, $superAdmin = 1);

		$this->adminPushNotification($this->getReceiverBranchManager($shipment),'RECEIVER_BRANCH_NOTIFY_SHIPMENT_UPCOMING', $receiverBranchParams, $receiverBranchAction);
		$this->adminFirebasePushNotification($this->getReceiverBranchManager($shipment), 'RECEIVER_BRANCH_NOTIFY_SHIPMENT_UPCOMING', $receiverBranchParams, $receiverBranchAction);
		$this->adminMail($this->getReceiverBranchManager($shipment), 'RECEIVER_BRANCH_MAIL_SHIPMENT_UPCOMING', $receiverBranchParams);

		$userAction = [
			"link" => route('user.shipmentList', ['shipment_status' => 'dispatch', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fal fa-truck text-white"
		];

		$this->userPushNotification($shipment->sender, 'SENDER_NOTIFY_SHIPMENT_DISPATCH', $senderParams, $userAction);
		$this->userFirebasePushNotification($shipment->sender, 'SENDER_NOTIFY_SHIPMENT_DISPATCH', $senderParams, $userAction);
		$this->sendMailSms($shipment->sender, 'SENDER_MAIL_SHIPMENT_DISPATCH', $senderParams);

		$this->userPushNotification($shipment->receiver, 'RECEIVER_NOTIFY_SHIPMENT_DISPATCH', $receiverParams);
		$this->userFirebasePushNotification($shipment->receiver, 'RECEIVER_NOTIFY_SHIPMENT_DISPATCH', $receiverParams);
		$this->sendMailSms($shipment->receiver, 'RECEIVER_MAIL_SHIPMENT_DISPATCH', $receiverParams);
	}

	public function receiveShipmentRequest($shipment){

		$senderParams = [
			'sender' => optional($shipment->sender)->name,
			'senderBranch' => optional($shipment->senderBranch)->branch_name,
			'receiverBranch' => optional($shipment->receiverBranch)->branch_name,
			'shipmentId' => $shipment->shipment_id,
			'receiveTime' => $shipment->receive_time,
		];

		$receiverParams = [
			'receiver' => optional($shipment->receiver)->name,
			'senderBranch' => optional($shipment->senderBranch)->branch_name,
			'receiverBranch' => optional($shipment->receiverBranch)->branch_name,
			'shipmentId' => $shipment->shipment_id,
			'receiveTime' => $shipment->dispatch_time,
		];

		$adminAction = [
			"link" => route('shipmentList', ['shipment_status' => 'received', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$this->adminPushNotification($this->getSenderBranchManager($shipment),'ADMIN_NOTIFY_SHIPMENT_RECEIVED', $senderParams, $adminAction, $superAdmin = 1);
		$this->adminFirebasePushNotification($this->getSenderBranchManager($shipment), 'ADMIN_NOTIFY_SHIPMENT_RECEIVED', $senderParams, $adminAction, $superAdmin = 1);
		$this->adminMail($this->getSenderBranchManager($shipment), 'ADMIN_MAIL_SHIPMENT_RECEIVED', $senderParams, $subject = null, $requestMessage = null, $superAdmin = 1);

		$userAction = [
			"link" => route('user.shipmentList', ['shipment_status' => 'received', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fal fa-truck text-white"
		];

		$this->userPushNotification($shipment->sender, 'SENDER_NOTIFY_SHIPMENT_RECEIVED', $senderParams, $userAction);
		$this->userFirebasePushNotification($shipment->sender, 'SENDER_NOTIFY_SHIPMENT_RECEIVED', $senderParams, $userAction);
		$this->sendMailSms($shipment->sender, 'SENDER_MAIL_SHIPMENT_RECEIVED', $senderParams);

		$this->userPushNotification($shipment->receiver, 'RECEIVER_NOTIFY_SHIPMENT_RECEIVED', $receiverParams);
		$this->userFirebasePushNotification($shipment->receiver, 'RECEIVER_NOTIFY_SHIPMENT_RECEIVED', $receiverParams);
		$this->sendMailSms($shipment->receiver, 'RECEIVER_MAIL_SHIPMENT_RECEIVED', $receiverParams);
	}

	public function deliveredShipmentRequest($shipment){

		$senderParams = [
			'sender' => optional($shipment->sender)->name,
			'receiver' => optional($shipment->receiver)->name,
			'senderBranch' => optional($shipment->senderBranch)->branch_name,
			'receiverBranch' => optional($shipment->receiverBranch)->branch_name,
			'shipmentId' => $shipment->shipment_id,
			'deliveredTime' => $shipment->receive_time,
		];

		$receiverParams = [
			'sender' => optional($shipment->sender)->name,
			'receiver' => optional($shipment->receiver)->name,
			'senderBranch' => optional($shipment->senderBranch)->branch_name,
			'receiverBranch' => optional($shipment->receiverBranch)->branch_name,
			'shipmentId' => $shipment->shipment_id,
			'deliveredTime' => $shipment->receive_time,
		];

		$adminAction = [
			"link" => route('shipmentList', ['shipment_status' => 'delivered', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$this->adminPushNotification($this->getSenderBranchManager($shipment),'ADMIN_NOTIFY_SHIPMENT_DELIVERED', $senderParams, $adminAction, $superAdmin = 1);
		$this->adminFirebasePushNotification($this->getSenderBranchManager($shipment), 'ADMIN_NOTIFY_SHIPMENT_DELIVERED', $senderParams, $adminAction, $superAdmin = 1);
		$this->adminMail($this->getSenderBranchManager($shipment), 'ADMIN_MAIL_SHIPMENT_DELIVERED', $senderParams, $subject = null, $requestMessage = null, $superAdmin = 1);


		$this->adminPushNotification($this->getReceiverBranchManager($shipment),'RECEIVER_BRANCH_NOTIFY_SHIPMENT_DELIVERED', $senderParams, $adminAction);
		$this->adminFirebasePushNotification($this->getReceiverBranchManager($shipment), 'RECEIVER_BRANCH_NOTIFY_SHIPMENT_DELIVERED', $senderParams, $adminAction);
		$this->adminMail($this->getReceiverBranchManager($shipment), 'RECEIVER_BRANCH_MAIL_SHIPMENT_DELIVERED', $senderParams);

		$userAction = [
			"link" => route('user.shipmentList', ['shipment_status' => 'delivered', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fal fa-truck text-white"
		];

		$this->userPushNotification($shipment->sender, 'SENDER_NOTIFY_SHIPMENT_DELIVERED', $senderParams, $userAction);
		$this->userFirebasePushNotification($shipment->sender, 'SENDER_NOTIFY_SHIPMENT_DELIVERED', $senderParams, $userAction);
		$this->sendMailSms($shipment->sender, 'SENDER_MAIL_SHIPMENT_DELIVERED', $senderParams);

		$this->userPushNotification($shipment->receiver, 'RECEIVER_NOTIFY_SHIPMENT_DELIVERED', $receiverParams);
		$this->userFirebasePushNotification($shipment->receiver, 'RECEIVER_NOTIFY_SHIPMENT_DELIVERED', $receiverParams);
		$this->sendMailSms($shipment->receiver, 'RECEIVER_MAIL_SHIPMENT_DELIVERED', $receiverParams);
	}

	public function deliveredConditionalShipment($shipment){

		$senderParams = [
			'sender' => optional($shipment->sender)->name,
			'receiver' => optional($shipment->receiver)->name,
			'senderBranch' => optional($shipment->senderBranch)->branch_name,
			'receiverBranch' => optional($shipment->receiverBranch)->branch_name,
			'shipmentId' => $shipment->shipment_id,
			'deliveredTime' => $shipment->receive_time,
		];

		$adminParams = [
			'sender' => optional($shipment->sender)->name,
			'receiver' => optional($shipment->receiver)->name,
			'senderBranch' => optional($shipment->senderBranch)->branch_name,
			'receiverBranch' => optional($shipment->receiverBranch)->branch_name,
			'shipmentId' => $shipment->shipment_id,
			'deliveredTime' => $shipment->receive_time,
			'currency' => config('basic.currency_symbol'),
			'receiveAmount' => $shipment->receive_amount,
		];

		$adminAction = [
			"link" => route('shipmentList', ['shipment_status' => 'delivered', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$this->adminPushNotification($this->getSenderBranchManager($shipment),'ADMIN_NOTIFY_CONDITION_SHIPMENT_DELIVERED', $adminParams, $adminAction, $superAdmin = 1);
		$this->adminFirebasePushNotification($this->getSenderBranchManager($shipment), 'ADMIN_NOTIFY_CONDITION_SHIPMENT_DELIVERED', $adminParams, $adminAction, $superAdmin = 1);
		$this->adminMail($this->getSenderBranchManager($shipment), 'ADMIN_MAIL_CONDITION_SHIPMENT_DELIVERED', $adminParams, $subject = null, $requestMessage = null, $superAdmin = 1);

		$userAction = [
			"link" => route('user.shipmentList', ['shipment_status' => 'delivered', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fal fa-truck text-white"
		];

		$this->userPushNotification($shipment->sender, 'SENDER_NOTIFY_CONDITION_SHIPMENT_DELIVERED', $senderParams, $userAction);
		$this->userFirebasePushNotification($shipment->sender, 'SENDER_NOTIFY_CONDITION_SHIPMENT_DELIVERED', $senderParams, $userAction);
		$this->sendMailSms($shipment->sender, 'SENDER_MAIL_CONDITION_SHIPMENT_DELIVERED', $senderParams);

		$this->userPushNotification($shipment->receiver, 'RECEIVER_NOTIFY_CONDITION_SHIPMENT_DELIVERED', $senderParams);
		$this->userFirebasePushNotification($shipment->receiver, 'RECEIVER_NOTIFY_CONDITION_SHIPMENT_DELIVERED', $senderParams);
		$this->sendMailSms($shipment->receiver, 'RECEIVER_MAIL_CONDITION_SHIPMENT_DELIVERED', $senderParams);
	}

	public function conditionShipmentPaymentConfirmToSender($shipment){
		$params = [
			'sender' => optional($shipment->sender)->name,
			'senderBranch' => optional($shipment->senderBranch)->branch_name,
			'currency' => config('basic.currency_symbol'),
			'amount' => $shipment->receive_amount,
			'shipmentId' => $shipment->shipment_id,
			'paymentGivenTime' => $shipment->condition_payment_time,
		];

		$adminAction = [
			"link" => route('shipmentList', ['shipment_status' => 'delivered', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$this->adminPushNotification($this->getSenderBranchManager($shipment),'ADMIN_NOTIFY_CONDITION_SHIPMENT_PAYMENT_GIVEN', $params, $adminAction, $superAdmin = 1);
		$this->adminFirebasePushNotification($this->getSenderBranchManager($shipment), 'ADMIN_NOTIFY_CONDITION_SHIPMENT_PAYMENT_GIVEN', $params, $adminAction, $superAdmin = 1);
		$this->adminMail($this->getSenderBranchManager($shipment), 'ADMIN_MAIL_CONDITION_SHIPMENT_PAYMENT_GIVEN', $params, $subject = null, $requestMessage = null, $superAdmin = 1);

		$userAction = [
			"link" => route('user.shipmentList', ['shipment_status' => 'delivered', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fal fa-truck text-white"
		];

		$this->userPushNotification($shipment->sender, 'SENDER_NOTIFY_CONDITION_SHIPMENT_PAYMENT_GIVEN', $params, $userAction);
		$this->userFirebasePushNotification($shipment->sender, 'SENDER_NOTIFY_CONDITION_SHIPMENT_PAYMENT_GIVEN', $params, $userAction);

		$this->sendMailSms($shipment->sender, 'SENDER_MAIL_CONDITION_SHIPMENT_PAYMENT_GIVEN', $params);
	}

	public function assignToCollectPickupShipment($shipment, $branchDriver){
		$params = [
			'branchDriver' => $branchDriver->name,
			'senderBranch' => optional($shipment->senderBranch)->branch_name,
			'shipmentId' => $shipment->shipment_id,
		];

		$driverAction = [
			"link" => route('shipmentList', ['shipment_status' => 'assign_to_collect', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$this->adminPushNotification($branchDriver,'BRANCH_DRIVER_NOTIFY_COLLECT_PICKUP_SHIPMENT', $params, $driverAction);
		$this->adminFirebasePushNotification($branchDriver, 'BRANCH_DRIVER_NOTIFY_COLLECT_PICKUP_SHIPMENT', $params, $driverAction);
		$this->adminMail($branchDriver, 'BRANCH_DRIVER_MAIL_COLLECT_PICKUP_SHIPMENT', $params);
	}

	public function assignToDeliveryPickupShipment($shipment, $branchDriver){
		$params = [
			'branchDriver' => $branchDriver->name,
			'receiverBranch' => optional($shipment->receiverBranch)->branch_name,
			'shipmentId' => $shipment->shipment_id,
		];

		$driverAction = [
			"link" => route('shipmentList', ['shipment_status' => 'assign_to_delivery', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$this->adminPushNotification($branchDriver,'BRANCH_DRIVER_NOTIFY_DELIVERY_PICKUP_SHIPMENT', $params, $driverAction);
		$this->adminFirebasePushNotification($branchDriver, 'BRANCH_DRIVER_NOTIFY_DELIVERY_PICKUP_SHIPMENT', $params, $driverAction);
		$this->adminMail($branchDriver, 'BRANCH_DRIVER_MAIL_DELIVERY_PICKUP_SHIPMENT', $params);
	}


	public function customerSendShipmentRequest($shipment, $sender){
		$params = [
			'sender' => $sender->name,
			'senderBranch' => optional($shipment->senderBranch)->branch_name,
			'shipmentId' => $shipment->shipment_id,
		];

		$adminAction = [
			"link" => route('shipmentList', ['shipment_status' => 'requested', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$userAction = [
			"link" => route('user.shipmentList', ['shipment_status' => 'requested', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$this->adminPushNotification($this->getSenderBranchManager($shipment),'ADMIN_NOTIFY_CUSTOMER_SENT_SHIPMENT_REQUEST', $params, $adminAction, $superAdmin = 1);
		$this->adminFirebasePushNotification($this->getSenderBranchManager($shipment), 'ADMIN_NOTIFY_CUSTOMER_SENT_SHIPMENT_REQUEST', $params, $adminAction, $superAdmin = 1);
		$this->adminMail($this->getSenderBranchManager($shipment), 'ADMIN_MAIL_CUSTOMER_SENT_SHIPMENT_REQUEST', $params, $subject = null, $requestMessage = null, $superAdmin = 1);

		$this->userPushNotification($sender, 'SENDER_NOTIFY_SEND_SHIPMENT_REQUEST', $params, $userAction);
		$this->userFirebasePushNotification($sender, 'SENDER_NOTIFY_SEND_SHIPMENT_REQUEST', $params, $userAction);
		$this->sendMailSms($sender, 'SENDER_MAIL_SEND_SHIPMENT_REQUEST', $params);
	}

	public function customerSendShipmentFromBranch($shipment, $sender){
		$params = [
			'sender' => $sender->name,
			'senderBranch' => optional($shipment->senderBranch)->branch_name,
			'amount' => $shipment->total_pay,
			'currency' => config('basic.currency_symbol'),
			'shipmentId' => $shipment->shipment_id,
		];

		$adminAction = [
			"link" => route('shipmentList', ['shipment_status' => 'in_queue', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$userAction = [
			"link" => route('user.shipmentList', ['shipment_status' => 'in_queue', 'shipment_type' => $this->getShipmentType($shipment)]),
			"icon" => "fas fa-truck text-white"
		];

		$this->adminPushNotification($this->getSenderBranchManager($shipment),'ADMIN_NOTIFY_CUSTOMER_SENT_SHIPMENT_FROM_BRANCH', $params, $adminAction, $superAdmin = 1);
		$this->adminFirebasePushNotification($this->getSenderBranchManager($shipment), 'ADMIN_NOTIFY_CUSTOMER_SENT_SHIPMENT_FROM_BRANCH', $params, $adminAction, $superAdmin = 1);
		$this->adminMail($this->getSenderBranchManager($shipment), 'ADMIN_MAIL_CUSTOMER_SENT_SHIPMENT_FROM_BRANCH', $params, $subject = null, $requestMessage = null, $superAdmin = 1);

		$this->userPushNotification($sender, 'SENDER_NOTIFY_CUSTOMER_SENT_SHIPMENT_FROM_BRANCH', $params, $userAction);
		$this->userFirebasePushNotification($sender, 'SENDER_NOTIFY_CUSTOMER_SENT_SHIPMENT_FROM_BRANCH', $params, $userAction);
		$this->sendMailSms($sender, 'SENDER_MAIL_CUSTOMER_SENT_SHIPMENT_FROM_BRANCH', $params);
	}

}
