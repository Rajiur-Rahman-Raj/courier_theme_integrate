<?php

namespace App\Services\Gateway\imepay;

use App\Models\ApiOrder;
use App\Models\ApiOrderTest;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\ProductOrder;
use App\Models\QRCode;
use App\Models\Voucher;
use Facades\App\Services\BasicService;
use Facades\App\Services\BasicCurl;


class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$MerchantModule = optional($gateway->parameters)->MerchantModule;
		$MerchantCode = optional($gateway->parameters)->MerchantCode;
		$username = optional($gateway->parameters)->username;
		$password = optional($gateway->parameters)->password;
		$url = "https://stg.imepay.com.np:7979/api/Web/GetToken";

		$postParam = array(
			"MerchantCode" => $MerchantCode,
			"Amount" => round($deposit->payable_amount, 2),
			"RefId" => $deposit->utr);

		$headers = array(
			'Content-Type: application/json',
			'Module: ' . base64_encode("{$MerchantModule}"),
			'Authorization: Basic ' . base64_encode("{$username}:{$password}")
		);


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParam));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		curl_close($ch);

		$checkResponse = json_decode($result);

		if ($checkResponse && isset($checkResponse->Message)) {
			$send['error'] = true;
			$send['message'] = "Error:" . @$checkResponse->Message;
			return json_encode($send);
		}

		$deposit->btc_wallet = @$checkResponse->TokenId;
		$deposit->save();

		$val['TokenId'] = $checkResponse->TokenId; //'IHzGMwNqGT24KHsD';
		$val['MerchantCode'] = optional($gateway->parameters)->MerchantCode;
		$val['RefId'] = $deposit->utr;
		$val['TranAmount'] = round($deposit->payable_amount, 2);
		$val['Method'] = 'GET';
		$val['RespUrl'] = route('ipn', [$gateway->code, $deposit->utr]);


		$CancelUrl = route('fund.initialize');
		$val['CancelUrl'] = $CancelUrl;
		$send['val'] = $val;
		$send['view'] = $deposit->depositable_type == Voucher::class || $deposit->depositable_type == Invoice::class || $deposit->depositable_type == QRCode::class || $deposit->depositable_type == ApiOrder::class || $deposit->depositable_type == ApiOrderTest::class ? 'frontend.payment.redirect' : ($deposit->depositable_type == ProductOrder::class ? 'user.store.payment.redirect' : 'user.payment.redirect');
		$send['method'] = 'post';
		$send['url'] = 'https://stg.imepay.com.np:7979/WebCheckout/Checkout';
		return json_encode($send);

	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{

		$imePayRes = $request->data;
		$res = base64_decode($imePayRes);
		$resArr = explode('|', $res);

		$order = Deposit::where('utr', $resArr[4])->orderBy('id', 'DESC')->with(['gateway'])->first();
		if ($order && $resArr[0] == 0 && $resArr[5] == round($order->payable_amount, 2) && $resArr[6] == $order->btc_wallet) {
			BasicService::prepareOrderUpgradation($order);
			$data['status'] = 'success';
			$data['msg'] = 'Transaction was successful.';
			$data['redirect'] = route('success');
		} else {
			$data['status'] = 'error';
			$data['msg'] = 'Invalid response.';
			$data['redirect'] = route('failed');
		}
		return $data;
	}
}
