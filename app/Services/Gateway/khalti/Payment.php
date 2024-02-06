<?php

namespace App\Services\Gateway\khalti;


use App\Models\ApiOrder;
use App\Models\ApiOrderTest;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\ProductOrder;
use App\Models\QRCode;
use App\Models\Voucher;
use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;

class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$send['publicKey'] = $gateway->parameters->public_key ?? '';
		$send['productIdentity'] = $deposit->utr;
		$send['eventOnSuccess'] = route('ipn', [$gateway->code, $deposit->utr]);
		$send['amount'] = round($deposit->payable_amount);
		$send['view'] = $deposit->depositable_type == Voucher::class || $deposit->depositable_type == Invoice::class || $deposit->depositable_type == QRCode::class || $deposit->depositable_type == ApiOrder::class || $deposit->depositable_type == ApiOrderTest::class ? 'frontend.payment.khalti' : ($deposit->depositable_type == ProductOrder::class ? 'user.store.payment.khalti' : 'user.payment.khalti');
		return json_encode($send);
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		$token = $request->token;
		$args = http_build_query(array(
			'token' => $token,
			'amount' => round($deposit->payable_amount) * 100
		));

		if ($gateway->environment == 'test' || $deposit->mode == 1) {
			$url = "https://a.khalti.com/api/v2/payment/verify/";
		} else {
			$url = "https://khalti.com/api/v2/payment/verify/";
		}

		# Make the call using API.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$secret_key = $gateway->parameters->secret_key ?? '';

		$headers = ["Authorization: Key $secret_key"];
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// Response
		$response = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$res = json_decode($response);

		if (isset($res->error_key) && $res->error_key == 'validation_error') {
			$data['status'] = 'error';
			$data['msg'] = 'Validation Error: ' . @$res->amount[0] . "<br>" . $res->token[0];
			$data['redirect'] = route('failed');
			return $data;
		}
		$forder = Deposit::where("utr", $res->product_identity)->orderBy('id', 'desc')->first();
		if ($forder) {
			BasicService::prepareOrderUpgradation($forder);
		}
	}
}
