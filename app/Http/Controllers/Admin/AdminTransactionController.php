<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTransactionController extends Controller
{
	public function index(Request $request)
	{

		$authenticateUser = Auth::guard('admin')->user();

		$filterData = $this->_filter($request);

		$search = $filterData['search'];
		$transactions = $filterData['transactions']
			->whereNotNull('shipment_type')
			->latest()
			->paginate(config('basic.paginate'));

		return view('admin.transaction.index', compact('transactions'));
	}

	public function _filter($request)
	{
		$search = $request->all();
		$fromDate = Carbon::parse($request->from_date);
		$toDate = Carbon::parse($request->to_date)->addDay();
		$authenticateUser = Auth::guard('admin')->user();

		$transactions = Transaction::with('transactional', 'branch.branchManager', 'user')
			->when(isset($authenticateUser->role_id), function ($query) use ($authenticateUser) {
				return $query->whereHas('branch.branchManager', function ($qry) use ($authenticateUser) {
					$qry->where(['admin_id' => $authenticateUser->id]);
				});
			})
			->when(@$search['trx_id'], function ($query) use ($search) {
				return $query->whereRaw("trx_id REGEXP '[[:<:]]{$search['trx_id']}[[:>:]]'");
			})
			->when(@$search['remark'], function ($query) use ($search) {
				return $query->where('remarks', 'LIKE', "%{$search['remark']}%");
			})
			->when(isset($search['from_date']), function ($query) use ($fromDate) {
				return $query->whereDate('created_at', '>=', $fromDate);
			})
			->when(isset($search['to_date']), function ($query) use ($fromDate, $toDate) {
				return $query->whereBetween('created_at', [$fromDate, $toDate]);
			});

		$data = [
			'transactions' => $transactions,
			'search' => $search,
		];
		return $data;
	}

	public function search(Request $request)
	{
		$filterData = $this->_filter($request);
		$search = $filterData['search'];
		$transactions = $filterData['transactions']
			->latest()
			->paginate();
		$transactions->appends($filterData['search']);
		return view('admin.transaction.index', compact('search', 'transactions'));
	}

}
