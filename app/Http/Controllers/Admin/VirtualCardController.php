<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\Transaction;
use App\Traits\Notify;
use App\Traits\Upload;

class VirtualCardController extends Controller
{
	use Upload, Notify;

	public function payout($code, Request $request)
	{
		$apiResponse = json_decode($request->all());
		if ($code == 'razorpay') {
			$this->razorpayPayoutWebhook($apiResponse);
		}
		if ($code == 'flutterwave') {
			$this->flutterwavePayoutWebhook($apiResponse);
		}
		if ($code == 'paystack') {
			$this->paystackPayoutWebhook($apiResponse);
		}
		if ($code == 'paypal') {
			$this->paypalPayoutWebhook($apiResponse);
		}
	}

	public function razorpayPayoutWebhook($apiResponse)
	{
		$basic = (object)config('basic');
		if ($apiResponse) {
			if ($apiResponse->payload) {
				if ($apiResponse->payload->payout) {
					if ($apiResponse->payload->payout->entity) {
						$payout = Payout::where('response_id', $apiResponse->payload->payout->entity->id)->first();
						$user = $payout->user;
						if ($payout) {
							if ($apiResponse->event == 'payout.processed' || $apiResponse->event == 'payout.updated') {
								if ($payout->status != 2) {
									$payout->status = 2;
									$payout->save();
									$this->userSuccessNotify($payout);
								}
							} elseif ($apiResponse->event == 'payout.rejected' || $apiResponse->event == 'payout.failed') {
								$payout->status = 6;
								$payout->last_error = $apiResponse->payload->payout->entity->status_details->description ?? '';
								$payout->save();

								$user->balance += $payout->transfer_amount;
								$user->save();

								$transaction = new Transaction();
								$transaction->user_id = $user->id;
								$transaction->amount = getAmount($payout->transfer_amount);
								$transaction->final_balance = $user->balance;
								$transaction->charge = $payout->charge;
								$transaction->trx_type = '+';
								$transaction->remarks = getAmount($payout->amount) . ' ' . $basic->currency . ' withdraw amount has been refunded';
								$transaction->trx_id = $payout->utr;
								$transaction->save();

								$this->userFailNotify($payout, $user);
							}
						}
					}
				}
			}
		}
	}


	public function flutterwavePayoutWebhook($apiResponse)
	{
		$basic = (object)config('basic');
		if ($apiResponse) {
			if ($apiResponse->event == 'transfer.completed') {
				if ($apiResponse->data) {
					$payout = Payout::where('response_id', $apiResponse->data->id)->first();
					$user = $payout->user;
					if ($payout) {
						if ($apiResponse->data->status == 'SUCCESSFUL') {
							$payout->status = 2;
							$payout->save();
							$this->userSuccessNotify($payout);
						}
						if ($apiResponse->data->status == 'FAILED') {
							$payout->status = 6;
							$payout->last_error = $apiResponse->data->complete_message;
							$payout->save();

							$user->balance += $payout->transfer_amount;
							$user->save();

							$transaction = new Transaction();
							$transaction->user_id = $user->id;
							$transaction->amount = getAmount($payout->transfer_amount);
							$transaction->final_balance = $user->balance;
							$transaction->charge = $payout->charge;
							$transaction->trx_type = '+';
							$transaction->remarks = getAmount($payout->amount) . ' ' . $basic->currency . ' withdraw amount has been refunded';
							$transaction->trx_id = $payout->utr;
							$transaction->save();

							$this->userFailNotify($payout, $user);
						}
					}
				}
			}
		}
	}


	public function paystackPayoutWebhook($apiResponse)
	{
		$basic = (object)config('basic');
		if ($apiResponse) {
			if ($apiResponse->data) {
				$payout = Payout::where('response_id', $apiResponse->data->id)->first();
				$user = $payout->user;
				if ($payout) {
					if ($apiResponse->event == 'transfer.success') {
						$payout->status = 2;
						$payout->save();
						$this->userSuccessNotify($payout);

					} elseif ($apiResponse->event == 'transfer.failed') {
						$payout->status = 6;
						$payout->last_error = $apiResponse->data->complete_message;
						$payout->save();
						$user->balance += $payout->transfer_amount;
						$user->save();

						$transaction = new Transaction();
						$transaction->user_id = $user->id;
						$transaction->amount = getAmount($payout->transfer_amount);
						$transaction->final_balance = $user->balance;
						$transaction->charge = $payout->charge;
						$transaction->trx_type = '+';
						$transaction->remarks = getAmount($payout->amount) . ' ' . $basic->currency . ' withdraw amount has been refunded';
						$transaction->trx_id = $payout->utr;
						$transaction->save();

						$this->userFailNotify($payout, $user);
					}
				}
			}
		}
	}


	public function paypalPayoutWebhook($apiResponse)
	{
		$basic = (object)config('basic');
		if ($apiResponse) {
			if ($apiResponse->batch_header) {
				$payout = Payout::where('response_id', $apiResponse->batch_header->payout_batch_id)->first();
				$user = $payout->user;
				if ($payout) {
					if ($apiResponse->event_type == 'PAYMENT.PAYOUTSBATCH.SUCCESS' || $apiResponse->event_type == 'PAYMENT.PAYOUTS-ITEM.SUCCEEDED' || $apiResponse->event_type == 'PAYMENT.PAYOUTSBATCH.PROCESSING') {
						if ($apiResponse->event_type != 'PAYMENT.PAYOUTSBATCH.PROCESSING') {
							$payout->status = 2;
							$payout->save();
							$this->userSuccessNotify($payout);
						}
					} else {
						$payout->status = 6;
						$payout->last_error = $apiResponse->summary;
						$payout->save();

						$user->balance += $payout->transfer_amount;
						$user->save();

						$transaction = new Transaction();
						$transaction->user_id = $user->id;
						$transaction->amount = getAmount($payout->transfer_amount);
						$transaction->final_balance = $user->balance;
						$transaction->charge = $payout->charge;
						$transaction->trx_type = '+';
						$transaction->remarks = getAmount($payout->amount) . ' ' . $basic->currency . ' withdraw amount has been refunded';
						$transaction->trx_id = $payout->utr;
						$transaction->save();

						$this->userFailNotify($payout, $user);
					}
				}
			}
		}
	}


	public function userSuccessNotify($data)
	{
		$user = $data->user;
		$basic = (object)config('basic');
		try {
			$this->sendMailSms($user, 'PAYOUT_APPROVE', [
				'method' => optional($data->payoutMethod)->methodName,
				'amount' => getAmount($data->amount),
				'charge' => getAmount($data->charge),
				'currency' => $basic->currency,
				'transaction' => $data->utr,
				'feedback' => $data->note,
			]);


			$msg = [
				'amount' => getAmount($data->amount),
				'currency' => $basic->currency,
			];
			$action = [
				"link" => route('payout.index'),
				"icon" => "fa fa-money-bill-alt"
			];

			$this->userPushNotification($user, 'PAYOUT_APPROVE', $msg, $action);
		} catch (\Exception $e) {

		}

		return 0;
	}

	public function userFailNotify($payout, $user)
	{
		$user = $payout->user;
		$basic = (object)config('basic');

		try {
			$this->sendMailSms($user, $type = 'PAYOUT_REJECTED', [
				'method' => optional($payout->payoutMethod)->methodName,
				'amount' => getAmount($payout->amount),
				'charge' => getAmount($payout->charge),
				'currency' => $basic->currency,
				'transaction' => $payout->utr,
				'feedback' => $payout->note,
			]);

			$msg = [
				'amount' => getAmount($payout->amount),
				'currency' => $basic->currency,
			];
			$action = [
				"link" => '#',
				"icon" => "fa fa-money-bill-alt "
			];

			$this->userPushNotification($user, 'PAYOUT_REJECTED', $msg, $action);
		} catch (\Exception $e) {

		}

		return 0;
	}

}
