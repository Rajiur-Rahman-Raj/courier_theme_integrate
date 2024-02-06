<?php

namespace App\Services;

use App\Models\Fund;
use App\Models\Transaction;
use App\Traits\Notify;
use Illuminate\Support\Facades\Auth;
use Image;

class BasicService
{
	use Notify;

	public function validateImage(object $getImage, string $path)
	{
		if ($getImage->getClientOriginalExtension() == 'jpg' or $getImage->getClientOriginalName() == 'jpeg' or $getImage->getClientOriginalName() == 'png') {
			$image = uniqid() . '.' . $getImage->getClientOriginalExtension();
		} else {
			$image = uniqid() . '.jpg';
		}
		Image::make($getImage->getRealPath())->resize(300, 250)->save($path . $image);
		return $image;
	}

	public function validateDate(string $date)
	{
		if (preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{2,4}$/", $date)) {
			return true;
		} else {
			return false;
		}
	}

	public function cryptoQR($wallet, $amount, $crypto = null)
	{

		$varb = $wallet . "?amount=" . $amount;
		return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$varb&choe=UTF-8";
	}

	public function validateKeyword(string $search, string $keyword)
	{
		return preg_match('~' . preg_quote($search, '~') . '~i', $keyword);
	}

	public function prepareOrderUpgradation($deposit)
	{

		$basicControl = basicControl();
		$deposit->status = 1;
		if ($deposit->depositable_type == Fund::class && !isset($deposit->depositable_id)) {
			/*
			 * Add money to wallet
			 * */
			$wallet = updateWallet($deposit->user_id, $deposit->amount, 1);

			$fund = new Fund();
			$fund->user_id = $deposit->user_id;
			$fund->percentage = $deposit->percentage;
			$fund->charge_percentage = $deposit->charge_percentage;
			$fund->charge_fixed = $deposit->charge_fixed;
			$fund->charge = $deposit->charge;
			$fund->amount = $deposit->amount;
			$fund->email = $deposit->email;
			$fund->status = 1;
			$fund->utr = $deposit->utr;
			$fund->save();

			$deposit->depositable_id = $fund->id;
			$transaction = new Transaction();
			$transaction->amount = $fund->amount;
			$transaction->charge = $fund->charge;
			$fund->transactional()->save($transaction);

			$params = [
				'amount' => $fund->amount,
				'currency' => $deposit->payment_method_currency,
				'transaction' => $fund->utr,
			];

			$action = [
				"link" => route('fund.index'),
				"icon" => "fa fa-money-bill-alt text-white"
			];
			$user = Auth::user();
			$this->sendMailSms($user, 'ADD_FUND_USER_USER', $params);
			$this->userPushNotification($user, 'ADD_FUND_USER_USER', $params, $action);
			$this->userFirebasePushNotification($user, 'ADD_FUND_USER_USER', $params, $action);

			$params = [
				'amount' => $fund->amount,
				'currency' => $deposit->payment_method_currency,
				'transaction' => $fund->utr,
			];

			$action = [
				"link" => '#',
				"icon" => "fa fa-money-bill-alt text-white"
			];

			$this->adminMail('ADD_FUND_USER_ADMIN', $params, $subject = null, $requestMessage = null, $superAdmin = 1);
			$this->adminPushNotification('ADD_FUND_USER_ADMIN', $params, $action);

		}
		$deposit->save();
		return true;
	}
}
