<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\Transaction;
use App\Traits\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;

class AdminPayoutController extends Controller
{
	use Notify;

	public function index()
	{
		$payouts = Payout::with(['user', 'user.profile', 'admin'])
			->latest()->paginate(config('basic.paginate'));
		return view('admin.payout.index', compact('payouts'));
	}

	public function search(Request $request)
	{
		$filterData = $this->_filter($request);
		$search = $filterData['search'];
		$payouts = $filterData['payouts']
			->latest()->paginate();
		$payouts->appends($filterData['search']);
		return view('admin.payout.index', compact('search', 'payouts'));
	}

	public function showByUser($userId)
	{
		$payouts = Payout::with(['user', 'user.profile', 'admin'])
			->where('user_id', $userId)
			->latest()->paginate();
		return view('admin.payout.index', compact('payouts', 'userId'));
	}

	public function searchByUser(Request $request, $userId)
	{
		$filterData = $this->_filter($request);
		$search = $filterData['search'];
		$payouts = $filterData['payouts']
			->where('user_id', $userId)
			->latest()
			->paginate();
		$payouts->appends($filterData['search']);
		return view('admin.payout.index', compact('search', 'payouts', 'userId'));
	}

	public function _filter($request)
	{
		$search = $request->all();
		$sent = isset($search['type']) ? preg_match("/sent/", $search['type']) : 0;
		$received = isset($search['type']) ? preg_match("/received/", $search['type']) : 0;
		$created_date = isset($search['created_at']) ? preg_match("/^[0-9]{2,4}-[0-9]{1,2}-[0-9]{1,2}$/", $search['created_at']) : 0;

		$payouts = Payout::with('user', 'user.profile', 'admin')
			->when(isset($search['email']), function ($query) use ($search) {
				return $query->where('email', 'LIKE', "%{$search['email']}%");
			})
			->when(isset($search['utr']), function ($query) use ($search) {
				return $query->where('utr', 'LIKE', "%{$search['utr']}%");
			})
			->when(isset($search['min']), function ($query) use ($search) {
				return $query->where('amount', '>=', $search['min']);
			})
			->when(isset($search['max']), function ($query) use ($search) {
				return $query->where('amount', '<=', $search['max']);
			})
			->when(isset($search['sender']), function ($query) use ($search) {
				return $query->whereHas('user', function ($qry) use ($search) {
					$qry->where('name', 'LIKE', "%{$search['sender']}%");
				});
			})

			->when($sent == 1, function ($query) use ($search) {
				return $query->where("user_id", Auth::id());
			})
			->when($received == 1, function ($query) use ($search) {
				return $query->where("receiver_id", Auth::id());
			})
			->when($created_date == 1, function ($query) use ($search) {
				return $query->whereDate("created_at", $search['created_at']);
			});

		$data = [
			'search' => $search,
			'payouts' => $payouts,
		];
		return $data;
	}

	public function show($utr)
	{
		$payout = Payout::with(['user', 'admin'])->where('utr', $utr)->first();

		if (!$payout) {
			return back()->with('alert', 'Transaction not found');
		}

		return view('admin.payout.show', compact('payout'));
	}

	public function confirmPayout(Request $request, $utr)
	{
		$payout = Payout::with(['user', 'admin'])->where('utr', $utr)->first();
		if (!$payout) {
			return back()->with('alert', 'Transaction not found');
		} elseif ($payout->status != 1) {
			return back()->with('alert', 'Action not possible');
		}
		$purifiedData = Purify::clean($request->all());

		$payout->note = $purifiedData['note'];
		$transaction = new Transaction();
		$transaction->amount = $payout->amount;
		$transaction->charge = $payout->charge;
		$payout->transactional()->save($transaction);
		$payout->status = 2;
		$payout->save();

		$receivedUser = $payout->user;
		$params = [
			'sender' => Auth::user()->name,
			'amount' => getAmount($payout->amount),
			'currency' => config('basic.base_currency'),
			'transaction' => $payout->utr,
		];

		$action = [
			"link" => route('payout.index'),
			"icon" => "fa fa-money-bill-alt text-white"
		];

		$this->sendMailSms($receivedUser, 'PAYOUT_CONFIRM', $params);
		$this->userPushNotification($receivedUser, 'PAYOUT_CONFIRM', $params, $action);
		$this->userFirebasePushNotification($receivedUser, 'PAYOUT_CONFIRM', $params, $action);

		return redirect(route('admin.payout.index'))->with('success', 'Payment Confirmed');

	}

	public function cancelPayout(Request $request, $utr)
	{
		$payout = Payout::with(['user', 'admin'])->where('utr', $utr)->first();
		if (!$payout) {
			return back()->with('alert', 'Transaction not found');
		} elseif ($payout->status != 1) {
			return back()->with('alert', 'Action not possible');
		}
		$purifiedData = Purify::clean($request->all());

		/*
		 * Add money from Sender Wallet
		 * */
		$sender_wallet = updateWallet($payout->user_id, $payout->transfer_amount, 1);

		$payout->note = $purifiedData['note'];
		$payout->status = 5;
		$payout->save();


		$receivedUser = $payout->user;
		$params = [
			'sender' => Auth::user()->name,
			'amount' => $payout->amount,
			'currency' => config('basic.base_currency'),
			'transaction' => $payout->utr,
		];

		$action = [
			"link" => route('payout.index'),
			"icon" => "fa fa-money-bill-alt text-white"
		];

		$this->sendMailSms($receivedUser, 'PAYOUT_CONFIRM', $params);
		$this->userPushNotification($receivedUser, 'PAYOUT_CONFIRM', $params, $action);
		$this->userFirebasePushNotification($receivedUser, 'PAYOUT_CONFIRM', $params, $action);

		return redirect(route('admin.payout.index'))->with('alert', 'Payment Canceled');

	}
}
