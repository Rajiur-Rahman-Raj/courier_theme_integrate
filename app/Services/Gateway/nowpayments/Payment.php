<?php

namespace App\Services\Gateway\nowpayments;

use App\Models\ApiOrder;
use App\Models\ApiOrderTest;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\ProductOrder;
use App\Models\QRCode;
use App\Models\Voucher;
use Facades\App\Services\BasicService;

class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$APIkey = $gateway->parameters->api_key ?? '';
		if ($gateway->environment == 'test' || $deposit->mode == 1) {
			$url = 'https://api-sandbox.nowpayments.io/v1/';
		} else {
			$url = 'https://api.nowpayments.io/v1/';
		}

		$postField['price_amount'] = (string)round($deposit->payable_amount, 2);
		$postField['price_currency'] = "USD";
		$postField['pay_currency'] = $deposit->payment_method_currency;
		$postField['ipn_callback_url'] = "https://nowpayments.io";
		$postField['order_id'] = $deposit->utr;
		$postField['order_description'] = "Deposit on " . config('basic.site_title') . " account";


		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url . 'payment',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode($postField),
			CURLOPT_HTTPHEADER => array(
				"x-api-key: $APIkey",
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);
		$result = json_decode($response);
		if (isset($result->status) && $result->status == false) {
			$send['error'] = true;
			$send['message'] = $result->message;
			$send['view'] = $deposit->depositable_type == Voucher::class || $deposit->depositable_type == Invoice::class || $deposit->depositable_type == QRCode::class || $deposit->depositable_type == ApiOrder::class || $deposit->depositable_type == ApiOrderTest::class ? 'frontend.payment.crypto' : ($deposit->depositable_type == ProductOrder::class ? 'user.store.payment.crypto' : 'user.payment.crypto');
			return json_encode($send);
		} else {
			$deposit['btc_wallet'] = $result->pay_address;
			$deposit['btc_amount'] = $result->pay_amount;
			$deposit['payment_id'] = $result->payment_id;
			$deposit->update();
		}


		$send['amount'] = $deposit->btc_amount;
		$send['sendto'] = $deposit->btc_wallet;
		$send['img'] = BasicService::cryptoQR($deposit->btc_wallet, $deposit->btc_amount);
		$send['currency'] = $deposit->payment_method_currency ?? 'BTC';
		$send['view'] = $deposit->depositable_type == Voucher::class || $deposit->depositable_type == Invoice::class || $deposit->depositable_type == QRCode::class || $deposit->depositable_type == ApiOrder::class || $deposit->depositable_type == ApiOrderTest::class ? 'frontend.payment.crypto' : ($deposit->depositable_type == ProductOrder::class ? 'user.store.payment.crypto' : 'user.payment.crypto');
		return json_encode($send);
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		$APIkey = $gateway->parameters->api_key ?? '';
		if ($gateway->environment == 'test') {
			$url = 'https://api-sandbox.nowpayments.io/v1/';
		} else {
			$url = 'https://api.nowpayments.io/v1/';
		}

		$orderData = Deposit::with('gateway')
			->whereHas('gateway', function ($query) {
				$query->where('code', 'nowpayments');
			})
			->where('status', 0)
			->where('btc_amount', '>', 0)
			->whereNotNull('btc_wallet')
			->whereNotNull('payment_id ')
			->latest()
			->get();

		foreach ($orderData as $data) {
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url . 'payment/' . $data->payment_id,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
					"x-api-key: $APIkey"
				),
			));

			$response = curl_exec($curl);
			curl_close($curl);

			$res = json_decode($response);

			if (isset($res->status) && $res->status == false) {
				continue;
			} else {
				if ($res->payment_status == 'finished') {
					BasicService::prepareOrderUpgradation($data);
				}

			}
		}
	}
}
